	<?php $this->beginWidget('bootstrap.widgets.BootModal', array('id'=>'foo')); ?>
 
		<div class="modal-header">
			<a class="close" data-dismiss="modal">&times;</a>
			<h3>Modal header</h3>
		</div>
		<?php echo CHtml::beginForm('','',array('id'=>'active-portlets-form'));?>    

		<div class="modal-body">

	<?php $portlets = Dashboard::model()->findAll();?>
	
			<div class="row-fluid">

				<h4> Select the portlets you want to be visible</h4>
				<?php 
					$data = array();
					$select = array();
						foreach($portlets as $portlet){
							if($portlet->active == 1){
							$checked = true;
							}else{
							$checked = false;
							}
								echo $portlet->title . "&nbsp;#". $portlet->id;
								echo CHtml::checkBox($portlet->id, $checked);  
								echo "<br/>";
						}
			?>
			</div>
		</div>
 
		<div class="modal-footer">
		<?php echo CHtml::submitButton("Save",array('class'=>'btn-primary  btn')); ?>

		<?php echo CHtml::endForm(); ?>

			<?php $this->widget('bootstrap.widgets.BootButton', array(
				'label'=>'Close',
				'url'=>'#',
				'htmlOptions'=>array('data-dismiss'=>'modal'),
			)); ?>
		</div>
		<?php $this->endWidget(); ?>
