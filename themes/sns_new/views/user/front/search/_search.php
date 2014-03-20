<?php
/* @var $this SearchController */
/* @var $model User */
/* @var $form CActiveForm */
?>
<div class="col">
    <div class="cell panel">
        <div class="body">
            <div class="cell">
                <div class="col" >
                    <div class="wide form cell">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
	'htmlOptions'=>array(
	'class'=>'',
	),
)); ?>

                                                    
                            <div class="col">
                                <div class="col width-1of4">
                                    <?php echo $form->label($model,'id'); ?>
                                </div>
                                <div class="col width-fill">
                                    <?php echo $form->textField($model,'id'); ?>
                                </div>
                            </div>

                                                    
                            <div class="col">
                                <div class="col width-1of4">
                                    <?php echo $form->label($model,'username'); ?>
                                </div>
                                <div class="col width-fill">
                                    <?php echo $form->textField($model,'username',array('size'=>20,'maxlength'=>20)); ?>
                                </div>
                            </div>

                                                                                
                            <div class="col">
                                <div class="col width-1of4">
                                    <?php echo $form->label($model,'icon_uri'); ?>
                                </div>
                                <div class="col width-fill">
                                    <?php echo $form->textField($model,'icon_uri',array('size'=>60,'maxlength'=>255)); ?>
                                </div>
                            </div>

                                                    
                            <div class="col">
                                <div class="col width-1of4">
                                    <?php echo $form->label($model,'email'); ?>
                                </div>
                                <div class="col width-fill">
                                    <?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>128)); ?>
                                </div>
                            </div>

                                                    
                            <div class="col">
                                <div class="col width-1of4">
                                    <?php echo $form->label($model,'activkey'); ?>
                                </div>
                                <div class="col width-fill">
                                    <?php echo $form->textField($model,'activkey',array('size'=>60,'maxlength'=>128)); ?>
                                </div>
                            </div>

                                                    
                            <div class="col">
                                <div class="col width-1of4">
                                    <?php echo $form->label($model,'superuser'); ?>
                                </div>
                                <div class="col width-fill">
                                    <?php echo $form->textField($model,'superuser'); ?>
                                </div>
                            </div>

                                                    
                            <div class="col">
                                <div class="col width-1of4">
                                    <?php echo $form->label($model,'status'); ?>
                                </div>
                                <div class="col width-fill">
                                    <?php echo $form->textField($model,'status'); ?>
                                </div>
                            </div>

                                                    
                            <div class="col">
                                <div class="col width-1of4">
                                    <?php echo $form->label($model,'create_at'); ?>
                                </div>
                                <div class="col width-fill">
                                    <?php echo $form->textField($model,'create_at'); ?>
                                </div>
                            </div>

                                                    
                            <div class="col">
                                <div class="col width-1of4">
                                    <?php echo $form->label($model,'lastvisit_at'); ?>
                                </div>
                                <div class="col width-fill">
                                    <?php echo $form->textField($model,'lastvisit_at'); ?>
                                </div>
                            </div>

                        
                        <div class="col ">
                            <div class="col width-1of4"></div>
                            <div class="col width-fill">
                                <div class="cell">
                                    <?php echo CHtml::submitButton('Search',array(
                                       'class'=>'button',
                                    )); ?>
                                </div>
                            </div>

                        </div>

                        <?php $this->endWidget(); ?>

                    </div><!-- search-form -->
                </div>
            </div>
        </div>
    </div>
</div>

