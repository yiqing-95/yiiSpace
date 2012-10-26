<?php
$this->breadcrumbs = array(
    'Css',
);?>
<h1><?php echo $this->id . '/' . $this->action->id; ?></h1>

<p>
    You may change the content of this page by modifying
    the file <tt><?php echo __FILE__; ?></tt>.
</p>


<style type="text/css">
    #target0 {
        background-color: #00aadf;
    }

    #target {
        background-color: #8844cc;
    }
    #target div{
        background-color:#cccccc;
        border: 2px solid #33333;
        width: 150px;
        height: 120px;
        margin: 0px auto;/*这句是居中的关键行*/
    }

</style>
<p>
    hi 一栏定宽居中测试啦 方式一使用align属性（非css样式 有时很方便哦 但有悖于css表现与内容分离的原则）
</p>
<div id="target0" align="center">
    <div>
        <img src="<?Php echo PublicAssets::instance()->url("images/user/avatars/5.jpg"); ?>"
             width="120px" height="120px"
             alt=""/>
    </div>
</div>

<div id="target" align="center">
    <div>
        <img src="<?Php echo PublicAssets::instance()->url("images/user/avatars/5.jpg"); ?>"
             width="120px" height="120px"
             alt=""/>
    </div>
</div>