<?php
/* @var $this NewsEntryController */
/* @var $model NewsEntry */
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
                                    <?php echo $form->textField($model,'id',array('size'=>10,'maxlength'=>10)); ?>
                                </div>
                            </div>

                                                    
                            <div class="col">
                                <div class="col width-1of4">
                                    <?php echo $form->label($model,'creator'); ?>
                                </div>
                                <div class="col width-fill">
                                    <?php echo $form->textField($model,'creator'); ?>
                                </div>
                            </div>

                                                    
                            <div class="col">
                                <div class="col width-1of4">
                                    <?php echo $form->label($model,'cate_id'); ?>
                                </div>
                                <div class="col width-fill">
                                    <?php echo $form->textField($model,'cate_id'); ?>
                                </div>
                            </div>

                                                    
                            <div class="col">
                                <div class="col width-1of4">
                                    <?php echo $form->label($model,'title'); ?>
                                </div>
                                <div class="col width-fill">
                                    <?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>255)); ?>
                                </div>
                            </div>

                                                    
                            <div class="col">
                                <div class="col width-1of4">
                                    <?php echo $form->label($model,'order'); ?>
                                </div>
                                <div class="col width-fill">
                                    <?php echo $form->textField($model,'order'); ?>
                                </div>
                            </div>

                                                    
                            <div class="col">
                                <div class="col width-1of4">
                                    <?php echo $form->label($model,'deleted'); ?>
                                </div>
                                <div class="col width-fill">
                                    <?php echo $form->textField($model,'deleted'); ?>
                                </div>
                            </div>

                                                    
                            <div class="col">
                                <div class="col width-1of4">
                                    <?php echo $form->label($model,'create_time'); ?>
                                </div>
                                <div class="col width-fill">
                                    <?php echo $form->textField($model,'create_time'); ?>
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

