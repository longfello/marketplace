<?php

/**
 * System settings view
 */
$this->pageHeader = Yii::t('CoreModule', 'Настройки');

$this->topButtons = $this->widget('admin.widgets.SAdminTopButtons', array(
	'form'=>$form,
	'template'=>array('history_back', 'save'),
));

$this->breadcrumbs = array(
	'Home'=>$this->createUrl('/admin'),
	Yii::t('CoreModule', 'Настройки'),
);
?>

<div class="form wide padding-all">
	<?php echo $form->asTabs(); ?>
</div>