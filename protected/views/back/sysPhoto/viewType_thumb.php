<li class="span3">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id),array('view','id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('categories')); ?>:</b>
	<?php echo CHtml::encode($data->categories); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('uid')); ?>:</b>
	<?php echo CHtml::encode($data->uid); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ext')); ?>:</b>
	<?php echo CHtml::encode($data->ext); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('size')); ?>:</b>
	<?php echo CHtml::encode($data->size); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('title')); ?>:</b>
	<?php echo CHtml::encode($data->title); ?>
	<br />

	<b><?php
        echo CHtml::encode($data->getAttributeLabel('uri')); ?>:</b>
	<?php
       echo CHtml::image(YsUploadStorage::instance()->getThumbUrl($data->uri,200,200),$data->title,array(
           'width'=>'200px',
           'height'=>'200px',
       ));
      // echo CHtml::encode($data->uri);
    ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('desc')); ?>:</b>
	<?php echo CHtml::encode($data->desc); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tags')); ?>:</b>
	<?php echo CHtml::encode($data->tags); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('create_time')); ?>:</b>
	<?php echo CHtml::encode($data->create_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('views')); ?>:</b>
	<?php echo CHtml::encode($data->views); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('rate')); ?>:</b>
	<?php echo CHtml::encode($data->rate); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('rate_count')); ?>:</b>
	<?php echo CHtml::encode($data->rate_count); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('cmt_count')); ?>:</b>
	<?php echo CHtml::encode($data->cmt_count); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('featured')); ?>:</b>
	<?php echo CHtml::encode($data->featured); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('hash')); ?>:</b>
	<?php echo CHtml::encode($data->hash); ?>
	<br />

	*/ ?>

	<?php echo CHtml::checkBox('ids[]',false,array('class'=>'batch-op-item','value'=>$data->id)); ?>

    <?php $this->widget('bootstrap.widgets.TbButtonGroup', array(
        'buttons'=>array(
            array(
                // yii中的ajax链接或者按钮 总是令人纠结 最好整理一些代码模板出来 用时照抄 改改就好！
                // ajax请求看看用途 有ajax链接 ajax按钮 还有对应的submit式的链接和按钮 后者需要包裹在form中
                //  根据你的需求选择不同的类型，这个可以从命令、请求的角度考虑 一般具有业务操作语义的命令最好放在form中！
                'label'=>CHtml::encode('设为封面'),
                'url'=>array('asAlbumCover','id'=>$data->id,'albumId'=>$albumId),
                'buttonType'=>TbButton::BUTTON_AJAXBUTTON,
                'ajaxOptions'=>array(
                    'dataType'=>'json',
                    'success'=>new CJavaScriptExpression('function(resp){
                        if(resp.status == "success"){
                            alert(resp.msg);
                        }else{
                            alert(resp.msg);
                        }
                  }'),
                ),
            ),
            array('label'=>CHtml::encode('查看'), 'url'=>array('view','id'=>$data->id)),
            array('label'=>CHtml::encode('编辑'), 'url'=>array('update','id'=>$data->id)),
            array('label'=>CHtml::encode('删除'), 'url'=>array('delete','id'=>$data->id),'htmlOptions'=>array('class'=>'delete')),
        ),
    ));
    ?>
</li>