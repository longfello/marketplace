<? $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array( 
	'id' => 'page-form',
	'inlineErrors' => false,
	'type' => 'horizontal',
)) ?>

	<?= $form->errorSummary($model) ?>

	<? $this->widget('bootstrap.widgets.TbTabs', array(
		'type' => 'tabs',
		'tabs' => array(
			array(
				'label' => 'Страница',
				'content' => $this->renderPartial('_content', array('form' => $form, 'model' => $model), true),
				'active' => true
			),
			array(
				'label' => 'SEO',
				'content' => $this->renderPartial('_seo', array('form' => $form, 'model' => $model), true),
			),
		),
	)) ?>

	<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <? $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'label' => $model->isNewRecord ? 'Добавить' : 'Сохранить',
      )) ?>
      <?php echo CHtml::link('Отмена', $this->createUrl('/UserAdmin/Pages'),array('class'=>'btn btn-default')); ?>
    </div>
	</div>

<? $this->endWidget() ?>
