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
    #category {
        width: 100px;
        border-right: 1px solid #c5c6c4;
        border-bottom: 1px solid #c5c6c4;
        border-left: 1px solid #c5c6c4;
    }

    #category h1 {
        margin: 0px;
        padding: 4px;
        font-size: 12px;
        font-weight: bold;
        border-top: 1px solid #c5c6c4;
        background-color: #f4f4f4;
    }

    #category h2 {
        margin: 0px;
        padding: 4px;
        font-size: 12px;
        font-weight: normal;
    }

</style>
<p>
    h1 顶部的border 自然会成为一个分割线 所以整个category 区域的border 并没有设置top边框
</p>
<div id="category">
    <h1>Css</h1>
    <h2>
        css入门
    </h2>
    <h2>
        css晋级
    </h2>
    <h2>css高级技巧</h2>
        <h1>Css</h1>
        <h2>
            css入门
        </h2>
        <h2>
            css晋级
        </h2>
        <h2>css高级技巧</h2>
        <h1>Css</h1>
        <h2>
            css入门
        </h2>
        <h2>
            css晋级
        </h2>
        <h2>css高级技巧</h2>

</div>
<div id="category2">
    <h1>Css</h1>
    <h2>
        css入门
    </h2>
    <h2>
        css晋级
    </h2>
    <h2>css高级技巧</h2>
    <h1>Css</h1>
    <h2>
        css入门
    </h2>
    <h2>
        css晋级
    </h2>
    <h2>css高级技巧</h2>
    <h1>Css</h1>
    <h2>
        css入门
    </h2>
    <h2>
        css晋级
    </h2>
    <h2>css高级技巧</h2>

</div>
    <style type="text/css">
        #category2 {
            width: 100px;
            border-color: #c5c6c4;
            border-style:  solid ;
            border-width: 0px 1px 1px 1px;
        }

        #category2 h1,#category2 h2 {
            margin: 0px;
            padding: 4px;
            font-size: 12px;
        }

        #category2 h1 {
           border-top: 1px solid #c5c6c4;
            background-color: #f4f4f4;
        }

        #category2 h2 {
            font-weight: normal;
        }
    </style>
