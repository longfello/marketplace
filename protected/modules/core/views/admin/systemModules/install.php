<?php
	/*** Display list of modules available for installation ***/

	$this->pageHeader = Yii::t('CoreModule', Yii::t('CoreModule', 'Список доступных модулей'));

	$this->breadcrumbs = array(
		'Home'=>$this->createUrl('/admin'),
		Yii::t('CoreModule', 'Модули')=>$this->createUrl('index'),
		Yii::t('CoreModule', 'Установка'),
	);

?>
<div class="padding-all">
	<?php if (!empty($modules)): ?>
		<?php foreach ($modules as $module=>$info): ?>
			<div>
				<b><?php echo CHtml::encode($info['name'].' '.$info['version']) ?></b>
				<p>
					<?php echo $info['description'] ?>
				</p>
				<p>
					<?php echo CHtml::link(Yii::t('CoreModule', 'Установить'), $this->createUrl('install', array('name'=>$module))) ?>
				</p>

				<br/>
			</div>
		<?php endforeach; ?>
	<?php else: ?>
		<?php Yii::t('CoreModule', 'Нет доступных модулей для установки.') ?>
	<?php endif; ?>
</div>