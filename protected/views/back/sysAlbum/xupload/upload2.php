<?php
$this->widget('xupload.XUpload', array(
    'url' => Yii::app()->controller->createUrl("handleUpload", array("albumId" => $albumId)),
    'model' => $model,//An instance of our model
    'attribute' => 'file',
    'multiple' => true,
    'formView'=>'tplDir.form',
    //Our custom upload template
    'uploadView' =>  'tplDir.tmpl-upload',
    //our custom download template
    'downloadView' => 'tplDir.tmpl-download',
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