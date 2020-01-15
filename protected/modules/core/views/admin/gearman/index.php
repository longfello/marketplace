<?php

	/** Display pages list **/
	$this->pageHeader = Yii::t('Gearman', 'Распределенные обработчики задач');

	$this->breadcrumbs = array(
		'Home'=>$this->createUrl('/admin'),
    Yii::t('Gearman', 'Распределенные обработчики задач'),
	);
?>

<ul class="nav nav-tabs">
  <li class="active"><a href="#tab-workers" data-toggle="tab"><?=Yii::t('Gearman', 'Потоки по серверам')?></a></li>
  <li><a href="#tab-tasks" data-toggle="tab"><?=Yii::t('Gearman', 'Фоновых задач в очереди:')?> <?=count($tasks)?></a></li>
  <li><a href="#tab-statuses" data-toggle="tab"><?=Yii::t('Gearman', 'Статус обработчиков задач')?></a></li>
</ul>

<!-- Tab panes -->
<div class="tab-content padding-all">
  <div class="tab-pane active" id="tab-workers">
    <?php foreach($servers as $key => $server) { ?>
      <div class="well">
        <h3><?=$server['host']?> (<?=Yii::t('Gearman', 'запущено потоков')?>: <?=count($server['pids'])?>)</h3>
        <a class="btn btn-success" href='<?=$this->createUrl('/admin/core/gearman/start', array('server'=>$key))?>'><?=Yii::t('Gearman', 'Добавить поток')?></a>
        <?php foreach($server['pids'] as $pid) { ?>
          <a class="btn btn-danger" href='<?=$this->createUrl('/admin/core/gearman/kill', array('pid'=>$pid, 'server'=>$key))?>' title="PID <?=$pid?>"><?=Yii::t('Gearman', 'Завершить поток');?></a>
        <?php } ?>
        <a class="btn btn-warning pull-right" href='<?=$this->createUrl('/admin/core/gearman/restart', array('server'=>$key))?>'><?=Yii::t('Gearman', 'Перезапустить все потоки')?></a>
      </div>
    <?php } ?>
    <a class="btn btn-warning pull-right" href='<?=$this->createUrl('/admin/core/gearman/restart')?>'><?=Yii::t('Gearman', 'Перезапустить все потоки всех серверов')?></a>
    <br><br>

  </div>
  <div class="tab-pane" id="tab-tasks">
    <table class="table table-stripped table-bordered table-hover">
      <tr>
        <th>#</th>
        <th><?=Yii::t('Gearman', 'Псевдоним задачи')?></th>
        <th><?=Yii::t('Gearman', 'id задачи')?></th>
        <th><?=Yii::t('Gearman', 'Статус задачи')?></th>
        <th><?=Yii::t('Gearman', 'Выполнение')?></th>
      </tr>
      <?php
        $i = 0;
        foreach($tasks as $tid => $status) {
          $i++;
          ?>
          <tr>
            <td><?=$i?></td>
            <td><?=$status->unique?></td>
            <td><?=$tid?></td>
            <td><?=$status->inProgress?Yii::t('Gearman', 'Обрабатывается'):Yii::t('Gearman', 'Ожидание')?></td>
            <td><?="{$status->numerator}/{$status->denominator} ({$status->percents}%)"?></td>
          </tr>
        <?php } ?>
    </table>
  </div>
  <div class="tab-pane" id="tab-statuses">
    <table class="table table-stripped table-bordered table-hover">
      <tr>
        <th><?=Yii::t('Gearman', 'Функция')?></th>
        <th><?=Yii::t('Gearman', 'Задач в очереде')?></th>
        <th><?=Yii::t('Gearman', 'Обработчиков в работе')?></th>
        <th><?=Yii::t('Gearman', 'Обработчиков всего')?></th>
      </tr>
      <?php foreach($statuses as $function => $val) {?>
        <tr>
          <td><?=$function?></td>
          <td><?=$val['total']?></td>
          <td><?=$val['running']?></td>
          <td><?=$val['workers']?></td>
        </tr>
      <?php } ?>
    </table>
  </div>
</div>






