<?php
$this->breadcrumbs=array(
	'Photo Albums'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List PhotoAlbum','url'=>array('index')),
	array('label'=>'Create PhotoAlbum','url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('photo-album-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Photo Albums</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button btn')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('bootstrap.widgets.TbExtendedGridView',array(
	'type' => 'striped bordered',
	'id'=>'photo-album-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'bulkActions' => array(
		'actionButtons' => array(
			array(
				'buttonType' => 'link', //'button',
				'type' => 'primary',
				'size' => 'small',
				'label' => 'bulkDelete',
                'url' => array('batchDelete'),
                'htmlOptions' => array(
                        'class'=>'bulk-action'
                ),
                    'click' => 'js:batchActions'
				)
			),
			// if grid doesn't have a checkbox column type, it will attach
			// one and this configuration will be part of it
			'checkBoxColumnConfig' => array(
				'name' => 'id'
			),
	),
	'columns'=>array(
		'id',
		'uid',
		'name',
		'create_time',
		'update_time',
		'cover_uri',
		/*
		'mbr_count',
		'views',
		'status',
		'is_hot',
		'privacy',
		'privacy_data',
		*/
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
		),
	),
)); ?>

<script type="text/javascript">
    // as a global variable
    var gridId = "photo-album-grid";

    $(function(){
        // 阻止 批处理按钮是链接时的跳转
        $(document).on('click','#photo-album-grid a.bulk-action',function() {
            return false;
        });
    });
    function batchActions(values){
        var url = $(this).attr('href');
        var ids = new Array();
        if(values.size()>0){
            values.each(function(idx){
                ids.push($(this).val());
            });
            $.ajax({
                type: "POST",
                url: url,
                data: {"ids":ids},
                dataType:'json',
                success: function(resp){
                    //alert( "Data Saved: " + resp);
                    if(resp.status == "success"){
                       $.fn.yiiGridView.update(gridId);
                    }else{
                        alert(resp.msg);
                    }
                }
            });
        }
    }
</script>