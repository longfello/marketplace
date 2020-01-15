<?
$this->pageTitle = 'Статические страницы';
$this->breadcrumbs = array(
	$this->pageTitle,
);
  Helper::registerCSS('jquery.treeTable.css');
  Helper::registerJS('jquery.treeTable.js', CClientScript::POS_END);
  Yii::app()->clientScript->registerScript('treetable', "
  $('.table').treeTable({
    expandable: true,
    initialState: 'expanded'
  });
  ");
?>

<div class="btn-toolbar">
	<?= CHtml::link('Создать страницу', array('create'), array('class' => 'btn btn-success')) ?>
</div>

<? $this->widget('TbGridViewTree', array(
	'id' => 'page-grid',
	'dataProvider' => $model->search(),
  'ajaxUpdate'   => true,
//  'ajaxType'     => 'POST',
	'filter' => $model,
	'afterAjaxUpdate'=>"js:function() {
$('.table').treeTable({
	expandable: true,
	initialState: 'expanded'
});
}",
	'columns' => array(
		array(
			'name' => 'id',
			'headerHtmlOptions' => array(
				'width' => 50,
			),
		),
		array(
			'name' => 'page_title_ru',
		),
		array(
			'name' => 'slug',
			'headerHtmlOptions' => array(
				'width' => 200,
			),
		),
		array(
			'class' => 'ext.jtogglecolumn.JToggleColumn',
			'name' => 'is_published',
			'filter' => array('0' => 'Нет', '1' => 'Да'),
			'checkedButtonLabel' => 'Снять с публикации',
			'uncheckedButtonLabel' => 'Опубликовать',
			'headerHtmlOptions' => array('width' => 100),
			'htmlOptions' => array('style' => 'text-align: center;'),
		),
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template' => '{update} {delete}',
		),
	),
)) ?>