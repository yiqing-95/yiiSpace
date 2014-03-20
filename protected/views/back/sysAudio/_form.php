<?php
  /*
   *  要学习好一个上传控件  必须研读所有能看懂的代码和文档！
   *  不要指望照抄代码就可以，看看人家优秀代码咋写的也利于提高自己哦！
   */

?>

<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'sys-audio-form',
    'type' => 'horizontal',
    // 'enableAjaxValidation' => true,
    'enableClientValidation' => true,
    'clientOptions' => array(
        'validateOnSubmit' => true,
        'afterValidate' => 'js:formAfterValidate',
    ),
)); ?>

<p class="help-block">Fields with <span class="required">*</span> are required.</p>

<?php echo $form->errorSummary($model); ?>

<?php echo $form->textFieldRow($model, 'uid', array('class' => 'span5')); ?>

<?php echo $form->textFieldRow($model, 'name', array('class' => 'span5', 'maxlength' => 120)); ?>

<?php echo $form->textFieldRow($model, 'singer', array('class' => 'span5', 'maxlength' => 60)); ?>

<?php echo $form->textFieldRow($model, 'summary', array('class' => 'span5', 'maxlength' => 500)); ?>

<?php echo $form->textFieldRow($model, 'uri', array('class' => 'span5', 'maxlength' => 255,'disabled'=>'true')); ?>

<div>
    <div>
        <span id="spanButtonPlaceholder"></span>
        (10 MB max)
    </div>
    <div class="flash" id="fsUploadProgress">
        <!-- This is where the file progress gets shown.  SWFUpload doesn't update the UI directly.
                    The Handlers (in handlers.js) process the upload events and make the UI updates -->
    </div>

    <!-- This is where the file ID is stored after SWFUpload uploads the file and gets the ID back from upload.php -->
</div>

<?php echo $form->dropdownListRow($model, 'source_type', SysAudio::getSourceTypeOptions(), array('class' => 'span5', 'maxlength' => 6)); ?>

<?php echo $form->textFieldRow($model, 'play_order', array('class' => 'span5')); ?>

<div style="display: none">
    <?php echo $form->textFieldRow($model, 'listens', array('class' => 'span5')); ?>

    <?php echo $form->textFieldRow($model, 'create_time', array('class' => 'span5')); ?>

    <?php echo $form->textFieldRow($model, 'cmt_count', array('class' => 'span5', 'maxlength' => 20)); ?>

    <?php echo $form->textFieldRow($model, 'glean_count', array('class' => 'span5')); ?>

    <?php echo $form->textFieldRow($model, 'file_size', array('class' => 'span5')); ?>

    <?php echo $form->textFieldRow($model, 'status', array('class' => 'span5')); ?>

</div>

<div class="form-actions">
    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'label' => $model->isNewRecord ? 'Create' : 'Save',
    )); ?>
</div>

<?php $this->endWidget(); ?>
<?php
/**
 * Created by PhpStorm.
 * User: yiqing
 * Date: 14-3-12
 * Time: 上午11:54
 */

//'use_query_string'=>true,
// Creating the URL that will handle our uploads
// check the parameter at the end
// $uurl = $this->createUrl('picture/upload',array('yourvarname'=>$yourvar));
$uurl = Yii::app()->request->getUrl();
//$uurl = $this->createUrl('sampleUpload');
Yii::import('ext.upload.CSwfUpload');
// here is our widget!
$this->widget('CSwfUpload', array(
// here you include the post params you want the
// upload function to handle when a file is submitted
//  'postParams'=>array('yourvarname'=>$yourvar),
        'config' => array(
            // api 变量 在其他地方可以使用它
            'apiVar'=>'swfu',

            'use_query_string' => false,
            //Use $this->createUrl method or define yourself
            'upload_url' => CHtml::normalizeUrl($uurl),
            // This is a workaround to avoid certain
            // issues (check SWFUpload Forums)
            'file_size_limit' => '2 gb',
            // Allowed file types
            'file_types' => '*.mp3;*.wav',
            // File types description (mine spanish)
            'file_types_description' => '音频文件',
            // unlimited number of files
            'file_upload_limit' => 0,
            // refer to handlers.js from here below
            'file_queue_error_handler' => 'js:fileQueueError',
            'file_dialog_complete_handler' => 'js:YiiSpace_fileDialogComplete',
            'file_queued_handler' => 'js:YiiSpace_fileQueued',

            'upload_progress_handler' => 'js:uploadProgress',
            'upload_error_handler' => 'js:uploadError',
            'upload_success_handler' => 'js:YiiSpace_uploadSuccess',
            'upload_complete_handler' => 'js:uploadComplete',
            // what is our upload target layer?
            'custom_settings' => array('upload_target' => 'fsUploadProgress'),
            // where are we going to place the button?
            'button_placeholder_id' => 'spanButtonPlaceholder',
            'button_width' => 230,
            'button_height' => 20,
            'button_text' => '<span class="button">选择图片最大2M (2 MB Max)</span>',
            'button_text_style' => '.button { font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif; font-size: 11pt; text-align: center; }',
            'button_text_top_padding' => 0,
            'button_text_left_padding' => 0,
            'button_window_mode' => 'js:SWFUpload.WINDOW_MODE.TRANSPARENT',
            'button_cursor' => 'js:SWFUpload.CURSOR.HAND',
        ),
    )
);

?>
<script type="text/javascript">
    /**
     * 复写掉原始默认的上传处理js文件中（handlers.js）的方法 可以择情复写哦！
     * 在上面配置下处理方法就好：  'upload_success_handler'=>'js:YiiSpace_uploadSuccess',
     *  swfuPath  这个路径是在CSwfUpload中注册的全局js 变量 可以直接使用哦
     *
     * @param file
     * @param serverData
     */
    function YiiSpace_uploadSuccess(file, serverData) {
        alert("服务端数据"+serverData);
        $("#msg").html(serverData);
        try {
            var progress = new FileProgress(file,  this.customSettings.upload_target);

            var resp = jQuery.parseJSON(serverData);

            if(resp.error == false){

                /*
                addImage(resp.fileUrl);
                 */
                progress.setStatus("缩略图创建成功！");
                progress.toggleCancel(false);
                alert("创建成功");
                window.location.href = resp.forward ;
            }else{
                addImage(swfuPath + "/images/error.gif");
                progress.setStatus("Error.");
                progress.toggleCancel(false);
                alert(serverData);
            }

            /*
            if (serverData.substring(0, 7) === "FILEID:") {
                addImage(serverData.substring(7));

                progress.setStatus("缩略图创建成功！");
                progress.toggleCancel(false);
            } else {
                addImage(swfuPath + "/images/error.gif");
                progress.setStatus("Error.");
                progress.toggleCancel(false);
                alert(serverData);
            }
          */

        } catch (ex) {
            this.debug(ex);
        }
    }

    function YiiSpace_fileDialogComplete(numFilesSelected, numFilesQueued) {
        try {
            if (numFilesQueued > 0) {
                // this.startUpload();
                // 不要立即开始上传！
                //  alert('准备开始了！');

            }
        } catch (ex) {
            this.debug(ex);
        }
    }

    /**
     * 文件刚被放人队列
     */
    function YiiSpace_fileQueued(file) {
        try {
            var txtFileName = document.getElementById("<?php echo Chtml::activeId($model,'uri'); ?>");
            txtFileName.value = file.name;
        } catch (e) {
        }

    }
    /**
     *
     */
    function fileDialogStart() {
        var txtFileName = document.getElementById("<?php echo Chtml::activeId($model,'uri'); ?>");
        txtFileName.value = "";

        alert("这是什么情况");

        this.cancelUpload();
    }

</script>
<div id="msg"></div>
<div id="results">
    表单内容这里！
</div>
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
     *
     * 注意 swfUpload js 的实例变量名称 是在上面设定的！
     */
    function formAfterValidate(form, data, hasError) {
         alert("触发验证！"+$(form).attr("action"))
        if (!hasError) {
            // 验证无错后 上传啦：序列化表单内容到swfUpload中
           var formFields = form.serializeArray();
            jQuery.each( formFields, function(i, field){
                $("#results").append(field.name + ": "+field.value);

                swfu.addPostParam(field.name,field.value);
            });
            // 添加完成后触发上传！ 其余的代码在swfu的回调函数中处理
            swfu.startUpload();

           //
            /*
            $.ajax({
                "type": "POST",
                "url": $(form).attr("action"),
                "data": form.serialize(),
                dataType: "json",
                "success": function (resp) {
                    // alert(resp);
                    if (resp.status == 'success') {

                        // $.fn.yiiGridView.update('supplier-goods-items-view');
                    }
                }
            });
            */
        }
        return false;
    }

</script>
