<?php
/**
 * File contains class WorkerJob
 *
 * @author Alexey Korchevsky <mitallast@gmail.com>
 * @link https://github.com/mitallast/yii-gearman
 * @copyright Alexey Korchevsky <mitallast@gmail.com> 2010-2011
 * @license https://github.com/mitallast/yii-gearman/blob/master/license
 */

/**
 * Class WorkerJob implements worker job interface realisation for gearman {@link http://gearman.org/}.
 * Object is transfer data to controller. Data may be only string, transfer don't use php- or JSON-serialisation.
 * It's not recommended use language-specific serialization, because workers may be written at another languages,
 * like C, C++, Python or Ruby.
 *
 * This is a wrapper to gearman job object. It's need to use alternative no-gearman realisation.
 *
 * @author Alexey Korchevsky <mitallast@gmail.com>
 * @package ext.worker
 * @version 0.2
 * @since 0.2
 */
class WorkerJob extends CComponent implements IWorkerJob
{
	private $job;
	/**
	 * Constructor.
	 *
	 * @param GearmanJob $job
	 */
	public function __construct(GearmanJob $job)
	{
		$this->job = $job;

    $model = GearmanTasks::model()->findByPk($job->unique());
    if ($model) {
      $model->delete();
    }

    $model = new GearmanTasks();
    $model->tid = $job->handle();
    $model->slug = $job->unique();
    $model->saveStatus = 1;
    if (!$model->save()) {
      echo("Error in query:\n");
      print_r($model->getErrors(), true);
      echo("\n\n");
    }

	}
	/**
	 * Get worker API called command name.
	 *
	 * @return string
	 */
	public function getCommandName()
	{
		return $this->job->functionName();
	}

	/**
	 * Get stream identifier.
	 *
	 * @return string
	 */
	public function getIdentifier()
	{
		return $this->job->unique();
	}

	/**
	 * Get data sending by task creator.
	 *
	 * @return string
	 */
	public function getWorkload()
	{
		return $this->job->workload();
	}

	/**
	 * Get unserialized data sending by task creator.
	 *
	 * @return string
	 */
	public function getParams()
	{
		return unserialize($this->job->workload());
	}

	/**
	 * Sends result data and the complete status update for this job.
     *
     * @link http://php.net/manual/en/gearmanjob.sendcomplete.php
     * @param string $result Serialized result data
     * @return bool
	 */
	public function sendComplete($data)
	{
    if (!is_string($data)) $data = serialize($data);
    echo('Job complete: #'.$this->getJob()->handle()."\n");
    $task = GearmanTasks::model()->find('tid = :tid', array(':tid' => $this->job->handle()));
    if ($task) {
      if ($task->saveStatus == 0) {
        echo('Remove job data from database: #'.$this->getJob()->handle()."\n");
        $task->delete();
      } else {
        echo('Save job data to database: #'.$this->getJob()->handle()."\n");
        $task->status = $data;
        if (!$task->save()) {
          echo('Error saving to database: #'.$this->getJob()->handle()."\n");
          print_r($task->getErrors());
          echo("\n\n");
        }
      }
    } else {
      echo("No row in database! \n");
    }

    // Зачистка
    GearmanTasks::model()->deleteAll('saveStatus = 0');
    GearmanTasks::model()->deleteAll('created < NOW() - INTERVAL 12 HOUR');

    echo('done'."\n\n");

		return $this->job->sendComplete($data);
	}

	/**
	 * Sends result data and the complete status update for this job.
     *
     * @link http://php.net/manual/en/gearmanjob.sendcomplete.php
     * @param string $result Serialized result data
     * @return bool
	 */
	public function sendStatus($numerator,$denominator)
	{
		return $this->job->sendStatus($numerator, $denominator);
	}

	/**
	 * Send exception to server or client error message.
	 *
	 * @param Exception|string $exception
	 * @return void
	 */
	public function sendException($exception)
	{
		return $this->job->sendException($exception);
	}

  public function getJob(){
    return $this->job;
  }

}