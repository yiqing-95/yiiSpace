<?php
/* @var $this GroupTopicPostController */
/* @var $model GroupTopicPost */
/* @var $form CActiveForm */
?>

<?php YsPageBox::beginPanel(); ?>

<div class="col cell">

    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'group-topic-post-form',
        'action' => array('groupTopicPost/create'),
        'enableAjaxValidation' => false,
        'enableClientValidation' => true,
        //'focus'=>array($model,''),
        'htmlOptions' => array(
            'class' => 'cell',
        ),
        'clientOptions' => array(
            'validateOnSubmit' => true,
            'afterValidate' => 'js:formAfterValidate',
        ),
    )); ?>
    <script type="text/javascript">
        /**
         * CActiveForm js/ajax 验证后会调用这个方法
         *
         * 这个方法如果返回true 那么就会进行正常的表单提交
         * 返回false后 一般进行手动ajax提交。
         * @param form
         * @param data
         * @param hasError
         * @returns {boolean}
         */
        function formAfterValidate(form, data, hasError) {
            // alert("触发验证！"+$(form).attr("action"));
            if (!hasError) {
                $.ajax({
                    "type": "POST",
                    "url": $(form).attr("action"),
                    "data": form.serialize(),
                    dataType: "json",
                    "success": function (resp) {
                        // alert(resp);
                        if (resp.status == 'success') {
                             alert('更新成功 稍候！');
                            setTimeout(function () {
                                $.fn.yiiListView.update('group-topic-post-list');
                               // reloadItemsView();
                            }, 2000);
                            // $.fn.yiiGridView.update('supplier-goods-items-view');
                        }
                    }
                });
            }
            return false;
        }
    </script>

    <div class="col">
        <p class="note">Fields with <span class="required">*</span> are required.</p>

        <?php echo $form->errorSummary($model); ?>
    </div>

    <?php echo $form->hiddenField($model, 'group_id'); ?>
    <?php echo $form->hiddenField($model, 'topic_id', array('size' => 11, 'maxlength' => 11)); ?>


    <div class="col">
        <div class="col width-1of4">
            <div class="cell">
                <?php echo $form->labelEx($model, 'content'); ?>
            </div>
        </div>
        <div class="col width-2of4">
            <div class="cell">
                <?php echo $form->textArea($model, 'content', array('rows' => 6, 'cols' => 50)); ?>
            </div>
        </div>

        <div class="col width-fill">
            <div class="cell">
                <?php echo $form->error($model, 'content'); ?>
            </div>
        </div>

    </div>


    <?php //  echo $form->textField($model, 'ip', array('size' => 16, 'maxlength' => 16)); ?>

    <?php // echo $form->textField($model,'create_time'); ?>

    <?php // echo $form->textField($model,'status'); ?>

    <?php // echo $form->textField($model,'is_del'); ?>


    <div class="col">
        <div class="col width-1of4">
        </div>
        <div class="col width-fill">
            <div class="cell">
                <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class' => 'button')); ?>
            </div>
        </div>
    </div>
    <?php $this->endWidget(); ?>

</div>

<?php YsPageBox::endPanel(); ?>

