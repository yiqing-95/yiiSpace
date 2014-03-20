<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
    'action'=>Yii::app()->createUrl($this->route),
    'method'=>'get',
    'htmlOptions'=>array('class'=>'well'),
)); ?>

<?php echo $form->textFieldRow($model,'username',array('class'=>'four columns','maxlength'=>20)); ?>

<div class="actions">
    <?php echo CHtml::submitButton('Search',array('class'=>'btn primary')); ?>
</div>

<?php $this->endWidget(); ?>