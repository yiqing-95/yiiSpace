<?php
/**
 * The following variables are available in this template:
 * - $this: the BootCrudCode object
 */
?>
<?php echo "<?php \$form=\$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'".$this->class2id($this->modelClass)."-form',
	'htmlOptions'=>array('class'=>'batch-update-form'),
	'enableAjaxValidation'=>false,
)); ?>\n"; ?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo "<?php echo \$form->errorSummary(\$model); ?>\n"; ?>

<?php
foreach($this->tableSchema->columns as $column)
{
	if($column->autoIncrement){
        continue;
    }
 ?>

<div class="alert">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>Warning!</strong> 如果不需要该字段 点击 "x" 删除掉该字段，不然会修改所有选中的行的！
    <?php echo "<?php echo ".$this->generateActiveRow($this->modelClass,$column)."; ?>\n"; ?>
</div>

<?php
}
?>
	<div class="form-actions">
		<?php echo "<?php \$this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>\$model->isNewRecord ? 'Create' : 'Save',
		)); ?>\n"; ?>
	</div>
<?php echo "<?php"; ?> echo CHtml::hiddenField('ids','', array('class' => 'batch-update-targets')); ?>
<?php echo "<?php \$this->endWidget(); ?>\n"; ?>
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
