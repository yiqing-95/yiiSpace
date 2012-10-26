
<?php echo "<?php"; ?> $this->widget('zii.widgets.CListView',array(
     'id'=>'<?php echo $this->class2id($this->modelClass); ?>-list',
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
