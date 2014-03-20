<?php
$this->widget('xupload.XUpload', array(
    'url' => Yii::app()->createUrl("test1/uploadAdditional", array("parent_id" => 1)),
    'model' => $model,//An instance of our model
    'attribute' => 'file',
    'multiple' => true,
    //Our custom upload template
    'uploadView' =>  'uploadDir.tmpl-upload',
    //our custom download template
    'downloadView' => 'downloadDir.tmpl-download',
    'options' => array(//Additional javascript options
        //This is the submit callback that will gather
        //the additional data  corresponding to the current file
        'submit' => "js:function (e, data) {
                    var inputs = data.context.find(':input');
                    data.formData = inputs.serializeArray();
                    return true;
                }"
    ),
));
?>