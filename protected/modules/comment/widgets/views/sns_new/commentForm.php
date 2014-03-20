<?php YsPageBox::beginPanel();
echo YiiUtil::getPathOfClass($model);

?>

<div class="col cell">


<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'comment-form',
    'action' => Yii::app()->createUrl('/comment/comment/add/'),
    'enableAjaxValidation'=>false,
    'enableClientValidation'=>true,
    //'focus'=>array($model,''),
    'htmlOptions' => array(
        'class'=>'cell',
    ),
    'clientOptions' => array(
        'validateOnSubmit'=>true,
        'afterValidate' => 'js:commentFormAfterValidate',
    ),

)); ?>

<div class="col">
    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>
</div>

<?php echo $form->hiddenField($model, 'model'); ?>
<?php echo $form->hiddenField($model, 'model_id'); ?>
<?php echo $form->hiddenField($model, 'model_owner_id'); ?>
<?php echo $form->hiddenField($model, 'model_profile_data'); ?>
<?php echo $form->hiddenField($model, 'parent_id'); ?>
<?php echo $form->hiddenField($model, 'user_id'); ?>
<?php echo CHtml::hiddenField('redirectTo', $redirectTo); ?>





<div class="col">
    <div class="col width-1of4">
        <div class="cell">
            <?php echo $form->labelEx($model,'url'); ?>
        </div>
    </div>
    <div class="col width-2of4">
        <div class="cell">
            <?php echo $form->textField($model,'url',array('size'=>60,'maxlength'=>150)); ?>
        </div>
    </div>

    <div class="col width-fill">
        <div class="cell">
            <?php echo $form->error($model,'url'); ?>
        </div>
    </div>

</div>

<div class="col">
    <div class="col width-1of4">
        <div class="cell">
            <?php echo $form->labelEx($model,'create_time'); ?>
        </div>
    </div>
    <div class="col width-2of4">
        <div class="cell">
            <?php echo $form->textField($model,'create_time'); ?>
        </div>
    </div>

    <div class="col width-fill">
        <div class="cell">
            <?php echo $form->error($model,'create_time'); ?>
        </div>
    </div>

</div>

<div class="col">
    <div class="col width-1of4">
        <div class="cell">
            <?php echo $form->labelEx($model,'name'); ?>
        </div>
    </div>
    <div class="col width-2of4">
        <div class="cell">
            <?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>150)); ?>
        </div>
    </div>

    <div class="col width-fill">
        <div class="cell">
            <?php echo $form->error($model,'name'); ?>
        </div>
    </div>

</div>

<div class="col">
    <div class="col width-1of4">
        <div class="cell">
            <?php echo $form->labelEx($model,'email'); ?>
        </div>
    </div>
    <div class="col width-2of4">
        <div class="cell">
            <?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>150)); ?>
        </div>
    </div>

    <div class="col width-fill">
        <div class="cell">
            <?php echo $form->error($model,'email'); ?>
        </div>
    </div>

</div>

    <?php
    $this->widget('ext.smileys.SmileysWidget',
        array(
            'group' => 'qq', // the group of smileys to be shown
            'cssFile' => 'smileys.css', // the file containing the CSS
            'scriptFile' => 'smileys.js', // javascript code for inserting the smileys into textareas
            'containerCssClass' => 'smileys', // the CSS class that wraps all smileys
            'wrapperCssClass' => 'smiley', // the CSS class that wrapps a single smiley
            'textareaId' => CHtml::activeId($model,'text'), // the ID of the textarea where we will put the smileys
            'perRow' => 0, // If this value is greather than 0, after $perRow smileys, a line break will be inserted.
            'forcePublish' => false, // set to true for one time, after you've added a new smiley group so that the group gets published to assets directory
        ));
    ?>
<div class="col">
    <div class="col width-1of4">
        <div class="cell">
            <?php echo $form->labelEx($model,'text'); ?>
        </div>
    </div>
    <div class="col width-2of4">
        <div class="cell">
            <?php echo $form->textArea($model,'text',array('rows'=>6, 'cols'=>50)); ?>
        </div>
    </div>

    <div class="col width-fill">
        <div class="cell">
            <?php echo $form->error($model,'text'); ?>
        </div>
    </div>

</div>


<div class="col">
    <div class="col width-1of4">
    </div>
    <div class="col width-fill">
        <div class="cell">
            <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save',array('class'=>'button')); ?>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>

</div>

<?php  YsPageBox::endPanel(); ?>

<script type="text/javascript">
    /**
     * CActiveForm js/ajax 验证后会调用这个方法
     *
     * 该方法中唯一注意点是要关闭掉modal对话框所以需要知道modalId
     *
     * 这个方法如果返回true 那么就会进行正常的表单提交
     * 返回false后 一般进行手动ajax提交。
     * @param form
     * @param data
     * @param hasError
     * @returns {boolean}
     */
    function commentFormAfterValidate(form, data, hasError) {
       // alert("触发验证！"+$(form).attr("action"));
        if (!hasError) {
            $.ajax({
                "type": "POST",
                "url": $(form).attr("action"),
                "data": form.serialize(),
                dataType: "json",
                "success": function (resp) {
                  //  alert(resp);
                    if (resp.status == 'success') {
                        /*
                        setTimeout(function () {
                            reloadItemsView("supplier-goods-items-view");
                        }, 2000);
                        // $.fn.yiiGridView.update('supplier-goods-items-view');
                        */
                        //probe the gridView or listView id
                        var listViewClass = 'div.comment.list-view';
                        /*
                        用来刷新listView
                        var listViewId
                             = $(listViewClass).attr('id');
                            // 添加到首部并滚动到那里去
                        */
                        $(listViewClass).find('.items').prepend(resp.commentContent);
                        $("html,body").animate({scrollTop: $(listViewClass).find('.items').children(0).position().top },800);//800 ms is also ok !
                        // 注意在yiiactiveform.js 中绑定的
                        $(form).trigger('reset');
                    }
                }
            });
        }
        return false;
    }

</script>

