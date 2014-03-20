<?php
/**
 * 本不提倡在视图中修改布局
 */
?>

<script type="text/javascript">
    $(function () {
        $('.extra_field').hide();
        $("input[name='status_type']").change(function () {
            $('.extra_field').hide();
            $('.' + $("input[name='status_type']:checked").val() +
                '_input').show();
            setStatusType($(this).val());
        });
    });

    function setStatusType(type){
        $(".status-type").val(type);
    }


    function refreshListOrGridView(){
        var listViewClass = 'list-view';
        var gridViewClass = 'grid-view';
        if($("."+listViewClass).size()>0){
            $.fn.yiiListView.update($("."+listViewClass,$('#myStatus')).attr("id"));
        }else{
            $.fn.yiiGridView.update($("."+gridViewClass,$('#myStatus')).attr("id"));
        }

    }
</script>


<div class="form well ">
    <?php $form = $this->beginWidget('CActiveForm', array(
    'id' => 'status-form',
    //'enableAjaxValidation' => true,
    'enableClientValidation'=>true,
    'method' => 'post',
    'htmlOptions' => array(
        'enctype' => 'multipart/form-data',
        'target'=>'helperFrame',
    ),
    'clientOptions' => array(
        'validateOnSubmit' => true,
    ),
)); ?>
    <div class="row-fluid">
        <div class="span11">
            <?php echo $form->errorSummary($model); ?>


            <?php echo $form->textArea($model, 'update', array('rows' => 6, 'cols' => 50, 'class' => 'span11 row')); ?>
            <div class="row-fluid">
                <div class="span8">

                    <input type="radio" name="status_type" id="status_checker_update"
                           class="status_checker" value="update"/>Update
                    <input type="radio" name="status_type" id="status_checker_video"
                           class="status_checker" value="video"/>Video
                    <input type="radio" name="status_type" id="status_checker_image"
                           class="status_checker" value="image"/>Image
                    <input type="radio" name="status_type" id="status_checker_link"
                           class="status_checker" value="link"/>Link
                    <br/>

                    <div class="video_input extra_field">
                        <label for="video_url" class="">YouTube URL</label>
                        <input type="text" id="" name="video_url" class=""/><br/>
                    </div>
                    <div class="image_input extra_field">
                        <label for="image_file" class="">Upload image</label>
                        <input type="file" id="" name="image_file" class=""/><br/>
                    </div>
                    <div class="link_input extra_field">
                        <label for="link_url" class="">Link</label>
                        <input type="text" id="" name="link_url" class=""/><br/>
                        <label for="link_description" class="">Description</label>
                        <input type="text" id="" name="link_description" class=""/><br/>
                    </div>

                    <?php  echo $form->hiddenField($model, 'type', array('class' => 'span5 status-type')); ?>

                    <?php  echo $form->hiddenField($model, 'creator', array('class' => 'span5')); ?>

                    <?php  echo $form->hiddenField($model, 'created', array('class' => 'span5')); ?>

                    <?php  echo $form->hiddenField($model, 'profile', array('class' => 'span5')); ?>

                    <?php  echo $form->hiddenField($model, 'approved', array('class' => 'span5')); ?>

                </div>
                <div class="span2 pull-right">
                    <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
                </div>
            </div>
        </div>
    </div>
    <?php $this->endWidget(); ?>

</div>
<div class="row"></div>
<iframe name="helperFrame"  style="display: none;"></iframe>
