<?php

  class Gearmand {
    private $defaultTimeout = 5;
    private $defaultSshPort = 22;
    private $cache_enabled = false;
    private $server;
    private $minWorkersPerServer = 0;

    public $servers = array();

    const PRIORITY_HIGH   = '3';
    const PRIORITY_NORMAL = '2';
    const PRIORITY_LOW    = '1';

    public function __construct(){
      $this->server = new GearmanClient();
    }

    public function init(){
      foreach($this->servers as $key => $server) {
        if (!isset($server['timeout']))  $this->servers[$key]['timeout']  = $server['timeout']  = $this->defaultTimeout;
        if (!isset($server['ssh_port'])) $this->servers[$key]['ssh_port'] = $server['ssh_port'] = $this->defaultSshPort;
        if (!isset($server['socket']))   $this->servers[$key]['socket']   = $server['socket']   = '';

        $this->servers[$key]['ssh']   = new Net_SSH2($server['host'], $server['ssh_port']);
        $this->server->addServer($server['host'], $server['port']);
        $this->connect($key);
        if (isset($server['ssh_user']) && isset($server['ssh_pass'])) {
          $this->servers[$key]['ssh']->login($server['ssh_user'], $server['ssh_pass']);
        } else {
          throw new Exception('No SSH parameters given');
        }
      }
    }

    public function addServer($host = '127.0.0.1', $port = 4730, $ssh_user = 'root', $ssh_pass = '', $ssh_port = 22) {
      $this->server->addServer($host, $port);
      $this->servers[] = array(
        'host'     => $host,
        'port'     => $port,
        'timeout'  => 5,
        'ssh_user' => $ssh_user,
        'ssh_pass' => $ssh_pass,
        'ssh_port' => $ssh_port,
        'socket'   => '',
        'ssh'      => new Net_SSH2($host, $ssh_port)
      );
      $server = count($this->servers)-1;
      $this->connect($server);
      if ($ssh_pass) {
        $this->servers[$server]['ssh']->login($this->servers[$server]['ssh_user'], $this->servers[$server]['ssh_pass']);
//        $this->ssh->exec('ls /etc/nginx/conf.d/vhost*'))
      } else {
        throw new Exception('No SSH parameters given');
      }
    }

    public function randomServer() {
      return $this->servers[rand(0, count($this->servers)-1)];
    }

    /**
     * returns array of status
     */
    function getStatus() {
      $status = array();
      for($serverIndex=0; $serverIndex<count($this->servers); $serverIndex++){
        $response = $this->getStatusRaw($serverIndex);
        // TODO build $response in a structured array
        $count = 0;
        $lines = explode("\n", $response );
        foreach( $lines as $line ) {
          if ( $line =='.' ) { break; }
          $parts = explode("\t", $line);
          $function = $parts[0];
          if (isset($status[$count])) {
            $status[$function]['total']    = max($parts[1], $status[$function]['total']);
            $status[$function]['running']  = max($parts[2], $status[$function]['running']);
            $status[$function]['workers']  = max($parts[3], $status[$function]['workers']);
          } else {
            if (isset($parts[3])) {
              $status[$function]['total']    = $parts[1];
              $status[$function]['running']  = $parts[2];
              $status[$function]['workers']  = $parts[3];
            }
          }
          $count++;
        }
      }
      return $status;
    }

    function startWorker($server = 0){
      $cmd = 'screen -d -m php '.Yii::app()->basePath. '/../worker.php';
      /* @var $ssh Net_SSH2 */
      $ssh = $this->servers[$server]['ssh'];
      $ssh->exec(sprintf('%s > /dev/null 2>&1 &', $cmd));
    }

    function stopWorker($pid, $server = 0){
      $cmd = 'kill '.$pid;
      /* @var $ssh Net_SSH2 */
      $ssh = $this->servers[$server]['ssh'];
      $ssh->exec($cmd);
    }

    function restartWorkers($server) {
      $pids = $this->getWorkersPids($server);
      foreach($pids as $pid) {
        $this->stopWorker($pid, $server);
        $this->startWorker($server);
      }
    }

    function getWorkersPids($server = 0){

      if (!$this->cache_enabled || !$workers = Yii::app()->cache->get('gearman_workers_'.$server)) {
        $workers = array();

        /* @var $ssh Net_SSH2 */
        $ssh = $this->servers[$server]['ssh'];
        $tmp = explode(PHP_EOL, $ssh->exec('ps aux | grep worker.php'));

        foreach($tmp as $one) {
          if (!strpos($one, 'grep') && !stripos($one, 'screen')) {
            $arr = explode(' ', $one);
            foreach($arr as $key => $item) {
              if (!trim($item)) unset($arr[$key]);
            }
            $arr = array_merge($arr);
            if (isset($arr[1])) {
              $workers[] = $arr[1];
            }
          }
        }
        if ($this->cache_enabled) {
          Yii::app()->cache->set('gearman_workers_'.$server, $workers, 30);
        }
      }
      return $workers;
    }

    /**
     * send the 'status' command to the server and return the raw response
     */
    function getStatusRaw($server = 0) {
      return $this->send($server, 'status');
    }

    function getWorkersCount(){
      $count = 0;
      foreach($this->servers as $key => $server) {
        $count_ = count($this->getWorkersPids($key));
        $limit = 10;
        while($count_ < $this->minWorkersPerServer && $limit > 0) {
          $this->startWorker($key);
          $count_ = count($this->getWorkersPids($key));
          $limit--;
        }
        $count += $count_;
      }
      return $count;
    }

    /**
     * return array of workers
     */
    function getWorkers($server = 0) {

      $response = $this->getWorkersRaw($server);
      $lines = explode("\n", $response );
      //TODO build $response in a structured array
      $count = 0;
      foreach( $lines as $line ) {
        if ( $line =='.' ) { break; }
        $parts = explode(" ", $line);
        $workers[$count]['descriptor'] = $parts[0];
        $workers[$count]['ip'] = $parts[1];
        $workers[$count]['clientid'] = $parts[2];
        $workers[$count]['functions'] = array();
        $func_marker = false;
        foreach( $parts as $part) {
          if ( $func_marker == true ) {
            $workers[$count]['functions'][] = $part;
          }
          if ($part == ':') { $func_marker = 1; }
        }
        if (!$workers[$count]['functions']) {
          unset($workers[$count]);
        } else {
          $count++;
        }
      }
      return $workers;
    }

    /**
     * send the 'workers' command to the server and return the raw response
     *
     */
    function getWorkersRaw($server = 0) {
      return $this->send($server, 'workers');
    }

    /**
     * connect to gearmand using tcp
     *
     */
    function connect($server = 0) {
      try {
        $this->servers[$server]['socket'] = fsockopen( $this->servers[$server]['host'], $this->servers[$server]['port'], $errno, $errstr, $this->servers[$server]['timeout']);
      } catch (Exception $e) {
        echo "Could not connect to gearmand server\n";
        echo "$errstr ($errno)\n";
      }
    }

    /**
     * disconnect from gearmand
     *
     */
    function disconnect($server) {
      fclose( $this->servers[$server]['socket']);
    }

    /**
     * send a command to the socket
     *
     */
    function send( $server = 0, $cmd ) {
      $data = '';
      if (!fwrite($this->servers[$server]['socket'], $cmd . "\r\n")) {
        trigger_error("gearman socket error!");
      } else {
        $i = 0;
        while (!feof($this->servers[$server]['socket'])) {
          $i++;
          $res = fgets($this->servers[$server]['socket'], 1024);
          if ($res === FALSE) break;
          $data .= $res;
          if ( preg_match("/\n\.$/i", $data ) ) { break; }
          if ($i > 50) trigger_error('Too deep recursion');
        }
      }
      return $data;
    }

    public function getTaskStatus($task_handle){
      $status = $this->server->jobStatus($task_handle);
      return new GearmanJobStatus($task_handle, $status);
    }

    public function doBackground($function_name, $workload ,$unique = null, $saveStatus = false, $priority=self::PRIORITY_NORMAL) {
      if ($this->getWorkersCount() > 0) {
        $unique = $unique?$unique:time().'-'.Helper::genCode(50);
        $tid = 0;
        switch($priority) {
          case self::PRIORITY_LOW:
            $tid = $this->server->doLowBackground($function_name, $workload, $unique);
            break;
          case self::PRIORITY_NORMAL:
            $tid = $this->server->doBackground($function_name, $workload, $unique);
            break;
          case self::PRIORITY_HIGH:
            $tid = $this->server->doHighBackground($function_name, $workload, $unique);
            break;
          default:
            trigger_error('Unknown priority given');
        }
      } else {
        $tid = 0;
        trigger_error('Невозможно завершить действие, так как отсутствуют обработчики действий. Обратитесь к администратору.');
      }

      return $tid;
    }
  }




  class GearmanJobStatus {
    public $done = true;
    public $inProgress = false;
    public $numerator = 0;
    public $denominator = 0;
    public $percents = 0;
    public $data = array();
    public $unique;

    private $job_handle;
    private $status;

    public function __construct($job_handle, $status){
      $this->job_handle = $job_handle;
      $this->status = $status;
      $this->getStatus();
    }

    public function getStatus(){
      if (is_array($this->status)) {
        if (count($this->status) == 4) {
          list($this->done, $this->inProgress, $this->numerator, $this->denominator) = $this->status;
          $this->done = !(bool)$this->done;
          if ($this->denominator) {
            $this->percents = round(100 * $this->numerator / $this->denominator);
          } else {
            $this->percents = $this->done?100:0;
          }
        }
      }

      $model = GearmanTasks::model()->find("tid = :tid", array(':tid' => $this->job_handle));
      if ($model) {
        $this->unique = $model->slug;
        $this->data = unserialize($model->status);
      } else {
        $this->data = 'none';
      }
      return $this;
    }
  }