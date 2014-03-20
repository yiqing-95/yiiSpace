<div class="wide form">

<?php
     echo CHtml::beginForm(array($this->route),'get',array(

     ));
?>

	<div class="row">
         <?php echo CHtml::textField('q','',array(

         )); ?>
	</div>

    <div class="row">
        <?php echo CHtml::radioButtonList('type',
            '',
            array(
            'blog'=>'博客',
            'photo'=>'图片',
            'user'=>'人',
        ),array(
          'container'=>'div'
        )); ?>
    </div>

    <div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php echo CHtml::endForm() ;?>

</div><!-- search-form -->