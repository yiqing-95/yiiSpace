<?php
/**
 * Created by PhpStorm.
 * User: yiqing
 * Date: 14-3-16
 * Time: 上午1:13
 */
YsAjaxHandler::registerClientErrorHandler('#ajax_error_container');
    ?>

<div id="ajax_error_container">

</div>
<div id="msg">

</div>
<?php
    echo CHtml::ajaxLink('测试ajax请求',Yii::app()->controller->createUrl(''),array(
        /*
        'error'=>'js:function(){
            alert("hhah");
        }',
        */
    ));
?>

<script type="text/javascript">
    jQuery.ajaxSetup( {}, {global:true} );
</script>

