<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 * 要使用yii的表单错误提示方法可把表单的enctype去掉，然后改onsubmit为下面这句，但是没上传功能了，再将控制器中，成功的的返回改成yii常规的重定向即可
 * 'onsubmit'=>\"return ".$this->class2id($this->modelClass)."_form(this)\"));?>\n";
 */
?>
<?php
echo "<?php echo CHtml::beginForm('', 'POST', array(
	'class'=>'pageForm required-validate',
	'onsubmit'=>'return iframeCallback(this,dialogAjaxDone)',
	'enctype'=>'multipart/form-data'))?>\n";
?>
<script type="text/javascript">
/*<![CDATA[*/
	function <?php echo $this->class2id($this->modelClass),'_form';?>(form){
		<?php echo "<?php echo \$_REQUEST['target']=='navTab'? 'navTab': '$.pdialog';?>"?>.reload(form.action, $(form).serializeArray());
		return false;
	}
/*]]>*/
</script>
<style>.alert .alertInner .msg{max-height:600px;overflow:visible;}</style>
	<div class="form pageFormContent" layoutH="55">
		<?php echo "<?php echo CHtml::errorSummary(\$model); ?>\n"; ?>

		<?php
		foreach($this->tableSchema->columns as $column)
		{
			if($column->isPrimaryKey)
				continue;
		?>
<div class="row">
			<?php echo "<?php echo CHtml::ActiveLabelEx(\$model,'$column->name'); ?>\n"; ?>
			<?php echo "<?php echo CHtml::ActiveTextField(\$model,'$column->name'); ?>\n"; ?>
			<?php echo "<?php echo CHtml::error(\$model,'$column->name'); ?>\n"; ?>
		</div>

		<?php
		}
		?>
	</div>
	<div class="formBar">
		<ul>
			<li><div class="buttonActive"><div class="buttonContent">
				<button type="submit"><?php echo "<?php echo \$model->isNewRecord ? '创建' : '保存'; ?>\n"; ?></button>
			</div></div></li>
			<li>
				<div class="button"><div class="buttonContent">
					<button onclick="<?php echo "<?php echo \$_REQUEST['target']=='navTab'? 'navTab.closeCurrentTab()': '$.pdialog.closeCurrent()';?>"?>" type="Button">取消</button>
				</div></div>
			</li>
		</ul>
	</div>
<?php echo "<?php echo CHtml::endForm() ?>\n"; ?>
</div>