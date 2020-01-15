<?php

  class CronCommand extends CConsoleCommand {
    const importInterval = 23; // 23 hours

    public function actionImport(){
      // php yiic.php cron import
      $criteria = new CDbCriteria();
      $criteria->addCondition("status = '".StoreImportSources::STATUS_APPROVED."'");
      $criteria->addCondition("lastRun < NOW() - INTERVAL ".self::importInterval." HOUR");
      $sources = StoreImportSources::model()->findAll($criteria);
      foreach($sources as $source) {
        echo('Starting import job for source #'.$source->id.' ('.$source->name.")\r\n");
        Yii::app()->gearman->doBackground("ImportRunCron", $source->id, 'ImportRunCron-'.$source->id, false, Gearmand::PRIORITY_LOW);
      }
    }
  }