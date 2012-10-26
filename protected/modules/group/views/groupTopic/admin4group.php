
<?php $this->widget('zii.widgets.grid.CGridView',array(
	'id'=>'group-topic-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'name',
		'creator',
		'created',
		'active',
		'group',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
