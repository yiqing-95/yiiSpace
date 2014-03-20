<style type="text/css">
    @import url(http://weloveiconfonts.com/api/?family=zocial);

        /* zocial */
    [class*="zocial-"]:before {
        font-family: 'zocial', sans-serif;
    }

</style>

}
一尘  22:29:29
<!-- Single Element -->
<span class="zocial-dribbble"></span>

<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id' => 'mydialog',
    // additional javascript options for the dialog plugin
    'options' => array(
        'title' => '选择商品',
        'autoOpen' => false,
        'width' => 980,
        'height' => 680,
        'buttons' => array(
            //CHtml::encode('新品提报') => new CJavaScriptExpression('function(){$(this).dialog("close");}'),
            //CHtml::encode('关闭')  => new CJavaScriptExpression('function(){$(this).dialog("close");}'),
        )
    ),
));
?>
 ren

<?php
$this->endWidget();
?>

<button onclick='$("#mydialog").dialog("open"); return false;'>点我</button>
