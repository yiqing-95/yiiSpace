
<div class="form" id="user_info_login_box">
    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'login-form',
        'action'=>array('/sys/runWidget'),
       // 'enableClientValidation'=>true,
        'enableAjaxValidation'=>true,
        'clientOptions'=>array(
            'validateOnSubmit'=>true,
            'afterValidate'=>'js:function(form,data,hasError){
                        if(!hasError){
                                $.ajax({
                                        "type":"POST",
                                        "url":"'.CHtml::normalizeUrl(array('/sys/runWidget')).'",
                                        "data":form.serialize(),
                                        "success":function(data){
                                           $("#user_info_login_box").html(data);
                                        },

                                        });
                                }
                        }'
        ),
    )); ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>


    <?php
    echo CHtml::hiddenField('class','user.widgets.pageblock.MemberBlock');
    ?>

    <div class="row">
        <?php echo $form->labelEx($model,'username'); ?>
        <?php echo $form->textField($model,'username'); ?>
        <?php echo $form->error($model,'username'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'password'); ?>
        <?php echo $form->passwordField($model,'password'); ?>
        <?php echo $form->error($model,'password'); ?>
        <p class="hint">
            Hint: You may login with <tt>demo/demo</tt> or <tt>admin/admin</tt>.
        </p>
    </div>

    <div class="row rememberMe">
        <?php echo $form->checkBox($model,'rememberMe'); ?>
        <?php echo $form->label($model,'rememberMe'); ?>
        <?php echo $form->error($model,'rememberMe'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton('Login'); ?>
    </div>

    <?php $this->endWidget(); ?>
</div><!-- form -->
