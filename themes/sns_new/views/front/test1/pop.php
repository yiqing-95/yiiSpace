<?php $this->widget('my.widgets.artDialog.ArtDialog'); ?>
<script type="text/javascript">
    // 普通调用
    $.dialog({content:'hello world!'});
</script>

<?php $this->widget('my.widgets.jpop.JPop'); ?>
<script type="text/javascript">
    $(function(){
        $.pop();
    });
</script>
<div>
    看左侧啊
    <div class='pop'>
        <p>you can put anything you want in here!</p>
        <p>images, links, movies of your cats. you name it!</p>
    </div>
</div>
