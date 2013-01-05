<?php

$this->breadcrumbs=array(
	Yii::t('backend', 'Setting'),
);?>

<h1><?php echo $this->id . '/' . $this->action->id; ?></h1>
<div class="form">
<?php /** @var BootActiveForm $form */
$form = $this->beginWidget('bootstrap.widgets.BootActiveForm', array(
    'id'=>'setting-form',
   // 'type'=>'horizontal',
)); ?>
	 <?php echo CHtml::errorSummary($model); ?>

	<div class="row">
		<?php echo CHtml::activeLabel($model,'site_name'); ?>
        <?php echo CHtml::activeTextField($model,'site_name') ?>
	</div>
	
	<div class="row">
		<?php echo CHtml::activeLabel($model,'site_url'); ?>
        <?php echo CHtml::activeTextField($model,'site_url') ?>
	</div>
	
	<div class="row">
		<?php echo CHtml::activeLabel($model,'keywords'); ?>
        <?php echo CHtml::activeTextField($model,'keywords') ?>
	</div>
	
	<div class="row">
		<?php echo CHtml::activeLabel($model,'description'); ?>
        <?php echo CHtml::activeTextArea($model,'description') ?>
	</div>
	
	<div class="row">
		<?php echo CHtml::activeLabel($model,'copyright'); ?>
        <?php echo CHtml::activeTextField($model,'copyright') ?>
	</div>
	
	<div class="row">
		<?php echo CHtml::activeLabel($model,'blogdescription'); ?>
        <?php echo CHtml::activeTextField($model,'blogdescription') ?>
	</div>
	<div class="row">
		<?php echo CHtml::activeLabel($model,'default_editor'); ?>
        <?php echo CHtml::activeTextField($model,'default_editor') ?>
	</div>
	<div class="row">
		<?php echo CHtml::activeLabel($model,'theme'); ?>
        <?php echo CHtml::activeTextField($model,'theme') ?>
	</div>
	<div class="row">
		<?php echo CHtml::activeLabel($model,'email'); ?>
        <?php echo CHtml::activeTextField($model,'email') ?>
	</div>

	<div class="row">
		<?php echo CHtml::activeLabel($model,'rss_output_num'); ?>
        <?php echo CHtml::activeTextField($model,'rss_output_num') ?>
	</div>
	<div class="row">
		<?php echo CHtml::activeLabel($model,'rss_output_fulltext'); ?>
        <?php echo CHtml::activeTextField($model,'rss_output_fulltext') ?>
	</div>
	<div class="row">
		<?php echo CHtml::activeLabel($model,'post_num'); ?>
        <?php echo CHtml::activeTextField($model,'post_num') ?>
	</div>
	<div class="row">
		<?php echo CHtml::activeLabel($model,'time_zone'); ?>
        <?php echo CHtml::activeTextField($model,'time_zone') ?>
	</div>
	
	<div class="row">
		<?php echo CHtml::activeLabel($model,'icp'); ?>
        <?php echo CHtml::activeTextField($model,'icp') ?>
	</div>
	
	<div class="row">
		<?php echo CHtml::activeLabel($model,'footer_info'); ?>
        <?php echo CHtml::activeTextField($model,'footer_info') ?>
	</div>
	
	<div class="row">
		<?php echo CHtml::activeLabel($model,'rewrite'); ?>
        <?php echo CHtml::activeTextField($model,'rewrite') ?>
	</div>
	
	<div class="row">
		<?php echo CHtml::activeLabel($model,'showScriptName'); ?>
        <?php echo CHtml::activeTextField($model,'showScriptName') ?>
	</div>
	
	<div class="row">
		<?php echo CHtml::activeLabel($model,'urlSuffix'); ?>
        <?php echo CHtml::activeTextField($model,'urlSuffix') ?>
	</div>
	
	<div class="row buttons">
		<?php $this->widget('zii.widgets.jui.CJuiButton', array(
			     	'name'=>'submit',
			  		'caption'=>Yii::t('backend', 'Save'),
			  		'options'=>array(
			          	'onclick'=>'js:function(){alert("Yes");}',
		  		),
		  ));
		?>
	</div>
	
<?php $this->endWidget(); ?>
</div><!-- form -->