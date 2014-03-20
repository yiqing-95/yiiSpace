<?php
/**
 * Created by PhpStorm.
 * User: yiqing
 * Date: 14-3-16
 * Time: 上午1:13
 */
?>
<div id="msg">

</div>
<?php
    echo CHtml::ajaxLink('测试ajax请求',Yii::app()->controller->createUrl(''),array(
        // 全局错误处理跟局部ajax错误处理的参数不一样！！！
        'error'=>'js:function (xhr, textStatus, errorThrown) {
                 // thrownError 只有当异常发生时才会被传递
                // this; // 监听的 dom 元素
                alert(xhr.responseText);
                $("#msg").html(xhr.responseText);
        }',

        'type'=>'POST',
    ));
?>


