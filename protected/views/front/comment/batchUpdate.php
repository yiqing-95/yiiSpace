<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'comment-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>


<div class="alert">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>Warning!</strong> 如果不需要该字段 点击 "x" 删除掉该字段，不然会修改所有选中的行的！
    <?php echo $form->textFieldRow($model,'object_name',array('class'=>'span5','maxlength'=>50)); ?>
</div>


<div class="alert">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>Warning!</strong> 如果不需要该字段 点击 "x" 删除掉该字段，不然会修改所有选中的行的！
    <?php echo $form->textFieldRow($model,'object_id',array('class'=>'span5')); ?>
</div>


<div class="alert">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>Warning!</strong> 如果不需要该字段 点击 "x" 删除掉该字段，不然会修改所有选中的行的！
    <?php echo $form->textFieldRow($model,'cmt_parent_id',array('class'=>'span5')); ?>
</div>


<div class="alert">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>Warning!</strong> 如果不需要该字段 点击 "x" 删除掉该字段，不然会修改所有选中的行的！
    <?php echo $form->textFieldRow($model,'author_id',array('class'=>'span5')); ?>
</div>


<div class="alert">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>Warning!</strong> 如果不需要该字段 点击 "x" 删除掉该字段，不然会修改所有选中的行的！
    <?php echo $form->textFieldRow($model,'user_name',array('class'=>'span5','maxlength'=>128)); ?>
</div>


<div class="alert">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>Warning!</strong> 如果不需要该字段 点击 "x" 删除掉该字段，不然会修改所有选中的行的！
    <?php echo $form->textFieldRow($model,'user_email',array('class'=>'span5','maxlength'=>128)); ?>
</div>


<div class="alert">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>Warning!</strong> 如果不需要该字段 点击 "x" 删除掉该字段，不然会修改所有选中的行的！
    <?php echo $form->textAreaRow($model,'cmt_text',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>
</div>


<div class="alert">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>Warning!</strong> 如果不需要该字段 点击 "x" 删除掉该字段，不然会修改所有选中的行的！
    <?php echo $form->textFieldRow($model,'create_time',array('class'=>'span5')); ?>
</div>


<div class="alert">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>Warning!</strong> 如果不需要该字段 点击 "x" 删除掉该字段，不然会修改所有选中的行的！
    <?php echo $form->textFieldRow($model,'update_time',array('class'=>'span5')); ?>
</div>


<div class="alert">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>Warning!</strong> 如果不需要该字段 点击 "x" 删除掉该字段，不然会修改所有选中的行的！
    <?php echo $form->textFieldRow($model,'status',array('class'=>'span5')); ?>
</div>


<div class="alert">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>Warning!</strong> 如果不需要该字段 点击 "x" 删除掉该字段，不然会修改所有选中的行的！
    <?php echo $form->textFieldRow($model,'replies',array('class'=>'span5')); ?>
</div>


<div class="alert">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>Warning!</strong> 如果不需要该字段 点击 "x" 删除掉该字段，不然会修改所有选中的行的！
    <?php echo $form->textFieldRow($model,'mood',array('class'=>'span5')); ?>
</div>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>
<?php echo CHtml::hiddenField('ids','', array('class' => 'batch-update-targets')); ?>
<?php $this->endWidget(); ?>
<script type="text/javascript">
   $(function(){
      // when ajax loaded this view  it will sync the ids to the hidden field :
       $(".batch-update-targets").val(getSelectedIds());// if you use iframe then call : parent.getSelectedIds()
   });
</script>
