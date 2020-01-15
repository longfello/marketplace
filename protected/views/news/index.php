<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_preview',
	'template'=>"{items}\n{pager}",
)); ?>


