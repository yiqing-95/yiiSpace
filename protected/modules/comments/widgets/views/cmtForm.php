<?php 
/**
 * @var Comment model
 */
?>

<div class="form cmt-form">
    not reply form!
<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'action'=>Yii::app()->urlManager->createUrl($this->postCommentAction),
        'id'=>$this->id,
    'htmlOptions'=>array(

    )
)); ?>
    <?php echo $form->errorSummary($newComment); ?>
    <?php 
        echo $form->hiddenField($newComment, 'object_name');
        echo $form->hiddenField($newComment, 'object_id');
        echo $form->hiddenField($newComment, 'cmt_parent_id', array('class'=>'cmt_parent_id'));
    ?>
    <?php if(Yii::app()->user->isGuest == true):?>
            <?php echo $form->textFieldRow($newComment,'user_name', array('size'=>40)); ?>

            <?php echo $form->textFieldRow($newComment,'user_email', array('size'=>40)); ?>
    <?php endif; ?>

        <?php echo $form->textAreaRow($newComment, 'cmt_text', array('class' =>'span6' , 'rows' =>4)); ?>

    <?php if($this->useCaptcha === true && extension_loaded('gd')): ?>
        <div class="row">
            <?php echo $form->labelEx($newComment,'verifyCode'); ?>
            <div>
                <?php $this->widget('CCaptcha', array(
                    'captchaAction'=>Yii::app()->urlManager->createUrl(CommentsModule::CAPTCHA_ACTION_ROUTE),
                )); ?>
                <?php echo $form->textField($newComment,'verifyCode'); ?>
                
            </div>
            <div class="hint">
                <?php echo Yii::t('CommentsModule.msg', '
                    Please enter the letters as they are shown in the image above.
                    <br/>Letters are not case-sensitive.
                ');?>
            </div>
            <?php echo $form->error($newComment, 'verifyCode'); ?>
        </div>
    <?php endif; ?>

    <div class="form-actions">
        <?php $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'button',
        'type' => 'primary',
        'icon' => 'ok white',
        'label' => 'ajaxSubmit',
        'htmlOptions'=>array(
            'onclick'=>"submitComment(this)"
        )
    )); ?>

    </div>
<?php $this->endWidget(); ?>
</div><!-- form -->

