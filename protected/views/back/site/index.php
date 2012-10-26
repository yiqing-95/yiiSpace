<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title><?php echo Yii::app()->name;?></title>
    <style type="text/css">
        .left-nav-link {
            cursor: pointer;
        }

        .hidden {
            display: none;
        }

        .activeTreeMenu {
        }

        #topNav li {
            float: left;
            list-style-type: none;
        }

        #topNav li a {
            color: #000000;
            text-decoration: none;
            padding-top: 4px;
            display: block;
            width: 97px;
            height: 22px;
            text-align: center;
            background-color: #ececec;
            margin-left: 2px;
        }

        #topNav li a:hover {
            background-color: #bbbbbb;
            color: #FFFFFF;
        }

        #topNav li a.current {
            background-color: #2788da;
            color: #FFFFFF;
        }

        #topNav {
            height: 26px;
            border-bottom: 2px solid #2788da;
        }
    </style>

    <?php
    cs()->registerCoreScript('jquery');
    PublicAssets::registerScriptFile('js/currentActive.js');
    ?>

    <script type="text/javascript">
        $(function () {
            $("#topNav").currentActive({target:'a', activeClass:'current'});
            //左侧菜单点击后目标显示窗体是 contentframe
            $("#left_side_nav a").attr('target', 'contentFrame');
            //隐藏所有左侧导航菜单先
            $("ul", "#left_side_nav").first().children().addClass('hidden');
            //顶级导航点击事件：
            $("a", "#topNav").click(function () {
                var thisId = $(this).attr('id');
                if (thisId == undefined || thisId.indexOf("menu") < 0) {
                    return;
                }
                var childrenId = "_" + thisId;
                if ($('.activeTreeMenu').size() > 0)
                    $('.activeTreeMenu').addClass('hidden').removeClass('activeTreeMenu');

                $("#" + childrenId).removeClass('hidden').addClass('activeTreeMenu');

                $("#" + childrenId + " li .selected").removeClass('selected');
                $("#" + childrenId).find('a:first').addClass('selected');
                //$("#"+childrenId).find('a:first').click();
                $("#contentFrame").attr('src', $("#" + childrenId).find('a:first').attr('href'));
            });

        });

        function refresh() {
            parent.contentFrame.location.reload(true);
        }
    </script>
</head>
<body>
<header id="header" class="row">
    <div class="row">
        <div class="logo two columns "></div>
        <div class="four columns">
            <a href="<?php echo $this->createUrl('/menuBuilder'); ?>" class="cWhite">menuBuilder</a>
              |
            <a title="frontend" href="<?php echo abu('index.php'); ?>" target="_blank" class="cWhite">frontEnd</a> |
            <a title="refresh" href="javascript:;" onclick='refresh()'>refresh</a> |
            <?php echo CHtml::link("logout(" . Yii::app()->user->name . ')', array('site/logout'), array('class' => 'cWhite')); ?>
        </div>
    </div>

    <div class="main_nav row">
        <ul id="topNav">
            <?php foreach ($roots as $menuNode): ?>
            <li>
                <?php
                //顶部菜单不做链接，只是做JS的菜单切换
                echo CHtml::link($menuNode->label, 'javascript:;', array('id' => 'menu' . $menuNode->id));
                ?>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
</header>

<div class="row ">
    <div class="leftmenu two columns">
        <div id='left_side_nav'>
            <?php $this->renderPartial('_leftSide', array('descendants' => $descendants)); ?>
        </div>

    </div>


    <div class="main ten columns ">
        <?php
        $this->widget('my.widgets.iframeAutoHeight.IFrameAutoHeight', array(
                //'debug' => false
            )
        );
        ?>
        <iframe src="http://localhost/my/yiiSpace/" name="contentFrame" id="contentFrame" width="100%" height="800px"/>

    </div>
</div>

</body>
</html>