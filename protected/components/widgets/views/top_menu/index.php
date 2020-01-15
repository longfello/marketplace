<ul class="menu">
  <li><?php $this->widget('application.components.widgets.staticPageLink', array('slug' => 'sales'))?></li>
  <li><?php $this->widget('application.components.widgets.staticPageLink', array('slug' => 'delivery'))?></li>
  <li><?php echo CHtml::link(Yii::t('widget-top_menu','Отзывы'),array('/feedback')); ?></li>
  <li><?php $this->widget('application.components.widgets.staticPageLink', array('slug' => 'contacts'))?></li>
</ul>