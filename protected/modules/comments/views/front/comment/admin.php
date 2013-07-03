<?php
$this->breadcrumbs=array(
	Yii::t('CommentsModule.msg', 'Comments')=>array('index'),
	Yii::t('CommentsModule.msg', 'Manage'),
);
?>

<h1><?php echo Yii::t('CommentsModule.msg', 'Manage Comments');?></h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'comment-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
                array(
                    'name'=>'object_name',
                    'htmlOptions'=>array('width'=>50),
                ),
                array(
                    'name'=>'object_id',
                    'htmlOptions'=>array('width'=>50),
                ),
                array(
                    'header'=>Yii::t('CommentsModule.msg', 'User Name'),
                    'value'=>'$data->userName',
                    'htmlOptions'=>array('width'=>80),
                ),
                array(
                    'header'=>Yii::t('CommentsModule.msg', 'Link'),
                    'value'=>'CHtml::link(CHtml::link(Yii::t("CommentsModule.msg", "Link"), $data->pageUrl, array("target"=>"_blank")))',
                    'type'=>'raw',
                    'htmlOptions'=>array('width'=>50),
		),
		'cmt_text',
                array(
                    'name'=>'create_time',
                    'type'=>'datetime',
                    'htmlOptions'=>array('width'=>70),
                    'filter'=>false,
                ),
		/*'update_time',*/
		array(
                    'name'=>'status',
                    'value'=>'$data->textStatus',
                    'htmlOptions'=>array('width'=>50),
                    'filter'=>Comment::model()->getStatuses(),
                ),
		array(
			'class'=>'CButtonColumn',
                        'deleteButtonImageUrl'=>false,
                        'buttons'=>array(
                            'approve' => array(
                                'label'=>Yii::t('CommentsModule.msg', 'Approve'),
                                'url'=>'Yii::app()->urlManager->createUrl(CommentsModule::APPROVE_ACTION_ROUTE, array("id"=>$data->cmt_id))',
                                'options'=>array('style'=>'margin-right: 5px;'),
                                'click'=>'function(){
                                    if(confirm("'.Yii::t('CommentsModule.msg', 'Approve this comment?').'"))
                                    {
                                        $.post($(this).attr("href")).success(function(data){
                                            data = $.parseJSON(data);
                                            if(data["code"] === "success")
                                            {
                                                $.fn.yiiGridView.update("comment-grid");
                                            }
                                        });
                                    }
                                    return false;
                                }',
				'visible'=>'$data->status == Comment::STATUS_NOT_APPROVED',
                            ),
                        ),
                        'template'=>'{approve}{delete}',
		),
	),
)); ?>
