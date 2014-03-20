<?php $this->widget('my.widgets.artDialog.ArtDialog'); ?>
<script type="text/javascript">
    // 普通调用
    $.dialog({content:'hello world!'});
</script>


<a href="#" id="ex2">Please, let me speak!</a>


<?php $this->widget('my.widgets.jpopover.JPopover'); ?>

<script type="text/javascript">
    $(function(){
        alert("hsdhfh");
        $("#ex2").popover({
           // trigger: 'hover',
           // trigger: 'click',
            title: "Hello",
            content: "Finally, I can speak!"
        });
alert("hsdhfh");
    });
   </script>


<style id="jsbin-css">
    .triangle-up {
        display: inline-block;
        width: 0;
        height: 0;
        border-style: solid;
        border-width: 0 45px 60px 45px;
        border-color: transparent transparent #000 transparent;
    }
    .triangle-down {
        display: inline-block;
        width: 0;
        height: 0;
        border-style: solid;
        border-width: 60px 45px  0  45px;
        border-color: transparent transparent #000 transparent;
        border-top-color: #000;
    }
</style>
参考这里

http://apps.eky.hk/css-triangle-generator/

<h3>牛逼的三角功能！</h3>
<div class="triangle-up"></div>
<div class="triangle-down"></div>

