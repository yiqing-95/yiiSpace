<?php $this->pageTitle=Yii::app()->name . ' - '.UserModule::t("Profile");

?>



<?php $this->widget('ext.metabox.EMetabox', array(
    'id' => 'mymetabox',
    'url' => array('/status/status/index','u'=>$_GET['u']),
    'refreshOnInit' => true,
    'options' => array(
        'afterRefresh' => 'js:function(data){
                  // alert("youyou");
                   }'
    ),
)); ?>
