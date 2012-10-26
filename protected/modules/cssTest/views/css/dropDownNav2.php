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
  ul{
      margin: 0;
      padding: 0;
      list-style: none;
      width: 130px;
      border-bottom: 1px solid #ccc;
      font-size: 12px;
  }
    ul li{
        position:relative;
    }
    li ul{
        position: absolute;
        left: 129px;
        top:0;
        display: none;
    }

    ul li a{
        display: block;
        text-decoration: none;
        color: #777;
        background: #fff;
        padding: 5px;
        border:1px solid #ccc;
        border-bottom:  0;
    }
    /*ie hack*/
    html ul li {
        float:left;
        height: 1%;
    }
    html ul li a{
        height: 1%;
    }

    li:hover ul , li.over ul{
        display: block;
    }



</style>
<script type="text/javascript">
    <?php cs()->registerCoreScript('jquery'); ?>
    /*
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
    }); */
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