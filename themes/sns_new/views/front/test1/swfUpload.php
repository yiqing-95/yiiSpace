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
$uurl = $this->createUrl('handleSwfUpload');
// here is our widget!
$this->widget('ext.upload.CSwfUpload', array(
// here you include the post params you want the
// upload function to handle when a file is submitted
//  'postParams'=>array('yourvarname'=>$yourvar),
        'config'=>array(
            'use_query_string'=>false,
            //Use $this->createUrl method or define yourself
            'upload_url'=> CHtml::normalizeUrl($uurl),
            // This is a workaround to avoid certain
            // issues (check SWFUpload Forums)
            'file_size_limit'=>'2 gb',
            // Allowed file types
            'file_types'=>'*.jpg;*.png;*.gif',
            // File types description (mine spanish)
            'file_types_description'=>'Imagenes',
            // unlimited number of files
            'file_upload_limit'=>0,
            // refer to handlers.js from here below
            'file_queue_error_handler'=>'js:fileQueueError',
            'file_dialog_complete_handler'=>'js:fileDialogComplete',
            'upload_progress_handler'=>'js:uploadProgress',
            'upload_error_handler'=>'js:uploadError',
            'upload_success_handler'=>'js:YiiSpace_uploadSuccess',
            'upload_complete_handler'=>'js:uploadComplete',
            // what is our upload target layer?
            'custom_settings'=>array('upload_target'=>'divFileProgressContainer'),
            // where are we going to place the button?
            'button_placeholder_id'=>'swfupload',
            'button_width'=>230,
            'button_height'=>20,
            'button_text'=>'<span class="button">选择图片最大2M (2 MB Max)</span>',
            'button_text_style'=>'.button { font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif; font-size: 11pt; text-align: center; }',
            'button_text_top_padding'=>0,
            'button_text_left_padding'=>0,
            'button_window_mode'=>'js:SWFUpload.WINDOW_MODE.TRANSPARENT',
            'button_cursor'=>'js:SWFUpload.CURSOR.HAND',
        ),
    )
);

?>

<script>
    /**
     * 复写掉原始默认的上传处理js文件中（handlers.js）的方法 可以择情复写哦！
     * 在上面配置下处理方法就好：  'upload_success_handler'=>'js:YiiSpace_uploadSuccess',
     *  swfuPath  这个路径是在CSwfUpload中注册的全局js 变量 可以直接使用哦
     *
     * @param file
     * @param serverData
     */
    function YiiSpace_uploadSuccess(file, serverData) {
        try {
            var progress = new FileProgress(file,  this.customSettings.upload_target);

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


        } catch (ex) {
            this.debug(ex);
        }
    }
</script>
<!-- SPANISH sorry -->
<div id="main-content">
    <h2>Cargador de Im&aacute;genes</h2>
    <p>Haz click en el siguiente boton para cargar las im&aacute;genes de la Propiedad.
        El cargador de Im&aacute;genes las cargar&aacute; y redimensionar&aacute; autom&aacute;ticamente.
    </p>
    <form>
        <div class="form">
            <div class="row">
                <div class="swfupload"  style="display: inline; border: solid 1px #7FAAFF; background-color: #C5D9FF; padding: 2px;">
                    <span id="swfupload"></span>
                </div>
                <div id="divFileProgressContainer" style="height: 75px;"></div>
                <div id="thumbnails">

                </div>
            </div>
        </div>
    </form>
</div>