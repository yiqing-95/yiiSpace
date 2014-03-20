<div class="easyui-layout" fit="true">
<?php echo "<?php \$form=\$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'".$this->class2id($this->modelClass)."-form',
	'action' => \$this->createUrl('save'),
		'type' => 'horizontal',
		'enableAjaxValidation' => true,
		'enableClientValidation' => TRUE,
		'clientOptions' => array(
			'validateOnSubmit' => true,
			'afterValidate' => 'js:".$this->class2var($this->modelClass)."_form_afterValidate'
		),

)); ?>\n"; ?>

<div data-options="region:'center',border:false">

<?php echo "<?php echo \$form->hiddenField(\$model, 'id'); ?>"?>
<?php
foreach($this->tableSchema->columns as $column)
{
	if($column->autoIncrement)
		continue;
?>
	<?php echo "<?php echo ".$this->generateActiveRow($this->modelClass,$column)."; ?>\n"; ?>

<?php
}
?>
	</div>
	<div data-options="region:'south',border:false" >
		<div class="form-actions " >
			<?php echo "<?php
			\$this->widget('bootstrap.widgets.TbButton', array(
				'buttonType' => 'submit',
				'type' => 'primary',
				'label' => Yii::t('base', 'Save')
			));

			\$this->widget('bootstrap.widgets.TbButton', array(
				'label' => Yii::t('base', 'Reset'),
				'htmlOptions' => array('action'=>'reset')
			));
			?>\n"
		?>
		</div>
	</div>

<?php echo "<?php \$this->endWidget(); ?>\n"; ?>
</div>