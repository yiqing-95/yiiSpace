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
    ul {
        padding: 0;
        margin: 0;
        list-style: none;
    }

    li {
        float: left;
        width: 160px;
    }

    li ul {
        display: none;
        top: 20px;
    }

    li:hover ul, li.over ul {
        display: block;
    }

        /* 里程碑1 */
    ul li a {
        display: block;
        font-size: 12px;
        border: 1px solid #ccc;
        padding: 3px;
        text-decoration: none;
        color: #777;

    }

    ul li a:hover {
        background-color: #f4f4f4;
    }
</style>
<script type="text/javascript">
    <?php cs()->registerCoreScript('jquery'); ?>
    $(function () {
        var $topMenus = $("#nav").children();
        $topMenus.each(function () {
            $(this).on("mouseover", function () {
                $(this).addClass('over');
            });
            $(this).on("mouseout", function () {
                $(this).removeClass('over');
            });
        });
    });
</script>
<p>
    h1 顶部的border 自然会成为一个分割线 所以整个category 区域的border 并没有设置top边框
</p>
<ul id="nav">
    <li class=""><a href="">文章</a>
        <ul>
            <li><a href="">css 教程</a></li>
            <li><a href="">dom 教程</a></li>
            <li><a href="">xml 教程</a></li>
            <li><a href="">js 教程</a></li>
            <li><a href="">html 教程</a></li>
        </ul>
    </li>
    <li>
        <a href="">参考</a>
        <ul>
            <li>
                <a href="">xhtml</a>
            </li>
            <li>
                <a href="">css</a>
            </li>

            <li>
                <a href="">js</a>
            </li>

            <li>
                <a href="">xml</a>
            </li>
        </ul>
    </li>
    <li><a href="">blog</a>
        <ul>
            <li><a href="">全部</a></li>
            <li><a href="">全部</a></li>
            <li><a href="">全部</a></li>
            <li><a href="">全部</a>
            <ul>
                <li><a href="">摇滚</a></li>
                <li><a href="">摇滚</a></li>
                <li><a href="">摇滚</a></li>
                <li><a href="">摇滚</a></li>
            </ul>
            </li>
        </ul>

    </li>
</ul>