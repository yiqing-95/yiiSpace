<script type="text/javascript">
    $(function () {
        $('.extra_field').hide();
        $("input[name='status_type']").change(function () {
            $('.extra_field').hide();
            $('.' + $("input[name='status_type']:checked").val() +
                '_input').show();
        });
    });
</script>


<div class="form well ">
    <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'status-form',
    'enableAjaxValidation' => false,
    'method' => 'post',
    'htmlOptions' => array(
        'enctype' => 'multipart/form-data'
    )
)); ?>
    <div class="row-fluid">
        <div class="span11">
            <?php //echo $form->errorSummary($model, 'Opps!!!', null, array('class' => 'alert alert-error row')); ?>


            <?php echo $form->textAreaRow($model, 'update', array('rows' => 6, 'cols' => 50, 'class' => 'span11 row')); ?>
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

                    <?php  echo $form->hiddenField($model, 'type', array('class' => 'span5')); ?>

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





        <div class="form-actions">

        </div>

    <?php $this->endWidget(); ?>

</div>
    <div class="row"></div>
