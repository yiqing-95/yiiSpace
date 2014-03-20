<?php echo "
<style>.alert .alertInner .msg{max-height:600px;overflow:visible;}</style>
<?php echo CHtml::beginForm('', 'POST', array('class'=>
			'pageForm required-validate',
			'enctype'=>'multipart/form-data',
			'onsubmit'=>\"return iframeCallback(this,dialogAjaxDone);\",
		));?>
	<div class='form pageFormContent' layoutH='55'>
		<div class='row'>
			<?php echo CHtml::textField('test'); ?>
		</div>
	<div class='formBar'>
		<ul>
			<li><div class='buttonActive'><div class='buttonContent'>
				<button type='submit'>保存</button>
			</div></div></li>
		</ul>
	</div>
<?php echo CHtml::endForm() ?>
</div>
	";