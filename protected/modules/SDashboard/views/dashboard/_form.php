<?php 	$type = ($model->isNewRecord) ? 'create' : $model->id;?>

	<?php $this->beginWidget('bootstrap.widgets.BootModal', array('id'=>'formModal')); ?>
 
		<div class="modal-header">
			<a class="close" data-dismiss="modal">&times;</a>
			<h3 id="maintitle"><?=($model->isNewRecord) ? 'Create Portlet' : 'Update Portlet';?></h3>
		</div>

		<div class="modal-body moveOut" id="formModalBody" style="width:90%;" >
		<?php $form=$this->beginWidget('bootstrap.widgets.BootActiveForm',array(
				'id'=>'dashboard-form',
			)); ?>

		<?php echo $form->errorSummary($model); ?>


		<?php echo $form->textFieldRow($model,'title',array('class'=>'span5','maxlength'=>255)); ?>

		<?php echo $form->textAreaRow($model,'content',array('class'=>'')); ?>
		<?php echo $form->checkboxRow($model, 'default'); ?>
		
		 <?php echo $form->textFieldRow($model,'ajaxrequest',array( 'cols'=>30, 'class'=>'span5'));  ?>
		 <?php echo $form->hiddenField($model,'newrecord',array('value'=>$type)); ?>

		</div>

 
		<div class="modal-footer">

		<?php echo CHtml::submitButton("Save",array('class'=>'btn-primary btn','id'=>'submitform') ); ?>
		<?php $this->endWidget(); ?>

			<?php $this->widget('bootstrap.widgets.BootButton', array(
				'label'=>'Close',
				'url'=>'#',
				'htmlOptions'=>array('data-dismiss'=>'modal'),
			)); ?>
		</div>
		<?php $this->endWidget(); ?>
<script type="text/javascript">
$().ready(function(){
 $('#Dashboard_content').markItUp(myBBcodeSettings);
});

</script>