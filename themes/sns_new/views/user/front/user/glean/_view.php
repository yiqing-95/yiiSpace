<?php
/* @var $this UserGleanController */
/* @var $data UserGlean */
?>

<div class="col">
    <div class="cell panel">
        <div class="body">
            <div class="cell">
                <div class="col">
                    <div class="cell">
                        <div class="col width-fit">
                            <b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
                            <?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
                            <br />

                        </div>
                        <div class="col width-fill">
                            <div class="cell">
                                <b><?php echo CHtml::encode($data->getAttributeLabel('user_id')); ?>:</b>
                                <?php echo CHtml::encode($data->user_id); ?>
                                <br />

                                <b><?php echo CHtml::encode($data->getAttributeLabel('object_type')); ?>:</b>
                                <?php echo CHtml::encode($data->object_type); ?>
                                <br />

                                <b><?php echo CHtml::encode($data->getAttributeLabel('object_id')); ?>:</b>
                                <?php echo CHtml::encode($data->object_id); ?>
                                <br />

                                <b><?php echo CHtml::encode($data->getAttributeLabel('ctime')); ?>:</b>
                                <?php echo CHtml::encode($data->ctime); ?>
                                <br />

                                <?php if( $data->user_id == user()->getId()):  ?>

                                  <?php echo WebUtil::ajaxDeleteLink('删除收藏',
                                        array('/user/gleanDelete','id'=>$data->primaryKey,),
                                     array(
                                         'beforeSend'=>'function(xhr){
                                         if(!confirm("确定删除该收藏?")) return false ;
                                    }',
                                          'success'=>'function(resp){
                                            //alert(resp);
                                            reloadGleanList();
                                          }
                                          ',
                                     ),
                                        array(
                                            'onclick'=>'gleanDeleteLink = this; ',
                                        )
                                    ); ?>
                                <?php endif ; ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div> 