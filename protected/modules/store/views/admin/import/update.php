<?php
  /* @var Controller $this */
  /* @var StoreImportSources $model */
  $this->pageHeader = Yii::t('StoreModule', 'Импорт товаров');

  $this->breadcrumbs = array(
    'Home'=>$this->createUrl('/admin'),
    Yii::t('StoreModule', 'Импорт товаров') => $this->createUrl('/admin/store/import'),
    $model->isNewRecord?Yii::t('StoreModule', 'Создание источника импорта'):Yii::t('StoreModule', 'Редактирование источника импорта'),
  );

  $this->topButtons = $this->widget('application.modules.admin.widgets.SAdminTopButtons', array(
    'template'=>array('create'),
    'elements'=>array(
      'create'=>array(
        'link'=>$this->createUrl('update'),
        'title'=>Yii::t('StoreModule', 'Создать источник импорта'),
        'options'=>array(
          'icons'=>array('primary'=>'ui-icon-plus')
        )
      ),
    ),
  ));

?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>

<?php if(Yii::app()->user->getIsSuperuser()) { ?>
<h3>Errors:</h3>
  <ol class='errors-wrapper' style="padding: 0 0 0 40px;">
<?php
  $condition = new CDbCriteria();
  $condition->addCondition('sid = '.(int)$model->id);
  $condition->order = 'created DESC';
  $errors = StoreImportErrors::model()->findAll($condition);
  foreach($errors as $error) {
    echo("<li style='list-style: inherit;'><div class='error-msg'><span class='text-muted'>$error->created&nbsp;&rArr;&nbsp;</span>$error->message</div><div class='errors-data'>$error->data</div></li>");
  }
?>
  </ol>

<style>
  .errors-wrapper .errors-data { display:none;}
  .errors-wrapper li:hover { border: 1px solid #999; background: #FFF; padding:5px;}
  .errors-wrapper li:hover .error-msg { font-weight: bold;}
  .errors-wrapper li:hover .errors-data { display:block;}
</style>

<?php } ?>