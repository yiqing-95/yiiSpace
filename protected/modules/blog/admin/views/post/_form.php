<div class="form">
<?php  $this->widget('ext.ueditor.Ueditor',
            array(
                'getId'=>'Post_content',
                'UEDITOR_HOME_URL'=>"/",
                'options'=>'toolbars:[["fontfamily","fontsize","forecolor","bold","italic","strikethrough","|","insertunorderedlist","insertorderedlist","blockquote","|","link","unlink","highlightcode","|","undo","redo","source"]],
                 	wordCount:false,
                 	elementPathEnabled:false,
                 	imagePath:"/attachment/ueditor/",
                 	initialContent:"",
                 	',
)); ?>

<?php $form=$this->beginWidget('bootstrap.widgets.BootActiveForm',array(
	'id'=>'post-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>
	
	<div class="row">
		<?php echo $form->errorSummary($model); ?>
	</div>
	
	<div class="row">
		<?php echo $form->textFieldRow($model,'title',array('class'=>'span5','maxlength'=>128)); ?>
	</div>
	
	<div class="row">
		<?php echo $form->dropDownList($model,'category_id',Category::CategoryList()); ?>
	</div>
	
	<div class="row">
		<?php echo $form->textAreaRow($model,'summary',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>
	</div>
	
	<div class="row">
		<?php echo $form->textAreaRow($model,'content',array('rows'=>6, 'cols'=>50)); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'tags'); ?>
		<?php $this->widget('CAutoComplete', array(
			'model'=>$model,
			'attribute'=>'tags',
			'url'=>array('suggestTags'),
			'multiple'=>true,
			'htmlOptions'=>array('size'=>50),
		)); ?>
		<p class="hint">Please separate different tags with commas.</p>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php echo $form->dropDownList($model,'status',Lookup::items('PostStatus')); ?>
	</div>
	
	
	<div class="row">
		<div class="form-actions">
			<?php $this->widget('bootstrap.widgets.BootButton', array(
				'buttonType'=>'submit',
				'type'=>'primary',
				'label'=>$model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Save'),
			)); ?>
		</div>
	</div>

<?php $this->endWidget(); ?>
</div>