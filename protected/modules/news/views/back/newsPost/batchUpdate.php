<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'news-post-form',
	'htmlOptions'=>array('class'=>'batch-update-form'),
	'enableAjaxValidation'=>false,
)); ?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>


<div class="alert">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>Warning!</strong> 如果不需要该字段 点击 "x" 删除掉该字段，不然会修改所有选中的行的！
    <?php echo $form->textFieldRow($model,'nid',array('class'=>'span5','maxlength'=>10)); ?>
</div>


<div class="alert">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>Warning!</strong> 如果不需要该字段 点击 "x" 删除掉该字段，不然会修改所有选中的行的！
    <?php echo $form->textAreaRow($model,'content',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>
</div>


<div class="alert">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>Warning!</strong> 如果不需要该字段 点击 "x" 删除掉该字段，不然会修改所有选中的行的！
    <?php echo $form->textFieldRow($model,'keywords',array('class'=>'span5','maxlength'=>100)); ?>
</div>


<div class="alert">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>Warning!</strong> 如果不需要该字段 点击 "x" 删除掉该字段，不然会修改所有选中的行的！
    <?php echo $form->textFieldRow($model,'meta_title',array('class'=>'span5','maxlength'=>255)); ?>
</div>


<div class="alert">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>Warning!</strong> 如果不需要该字段 点击 "x" 删除掉该字段，不然会修改所有选中的行的！
    <?php echo $form->textFieldRow($model,'meta_description',array('class'=>'span5','maxlength'=>255)); ?>
</div>


<div class="alert">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>Warning!</strong> 如果不需要该字段 点击 "x" 删除掉该字段，不然会修改所有选中的行的！
    <?php echo $form->textFieldRow($model,'meta_keywords',array('class'=>'span5','maxlength'=>255)); ?>
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
       // if you use iframe then call : parent.getSelectedIds()
       var selectedIds = getSelectedIds();
       if(jQuery.trim(selectedIds).length == 0){
          $(".batch-update-form").html('最少选择一项！');
       }else{
           // when ajax loaded this view  it will sync the ids to the hidden field :
           $(".batch-update-targets").val(selectedIds);
       }

   });
</script>
