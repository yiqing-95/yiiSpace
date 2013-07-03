<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title><?php echo Yii::app()->name;?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Free yii themes, free web application theme">
    <meta name="author" content="Webapplicationthemes.com">
    <link href='http://fonts.googleapis.com/css?family=Carrois+Gothic' rel='stylesheet' type='text/css'>

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <style type="text/css">
        .left-nav-link {
            cursor: pointer;
        }

        .hidden {
            display: none;
        }

        .activeTreeMenu {
        }
    </style>

    <?php
    cs()->registerCoreScript('jquery');
    PublicAssets::registerScriptFile('js/currentActive.js');
    PublicAssets::cssFile('assets4back/abound/css/abound.css');
    ?>

    <script type="text/javascript">
        $(function () {
           // $("#topNav").currentActive({target:'a', activeClass:'active'});
            $("#topNav").currentActive({target:'li', activeClass:'active'});
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
<header class="header">
    <div class="row-fluid">
        <div class="span1">
            <?php echo CHtml::image(bu('public/images/yii.png')); ?>
        </div>
        <div class="span10">
            <div class="row-fluid">
                <div class="span4 pull-right">
                    <a href="<?php echo $this->createUrl('/menuBuilder'); ?>" class="cWhite">menuBuilder</a>
                      |
                    <a title="frontend" href="<?php echo abu('index.php'); ?>" target="_blank"
                       class="cWhite">frontEnd</a> |
                    <a title="refresh" href="javascript:;" onclick='refresh()'>refresh</a> |
                    <?php echo CHtml::link("logout(" . Yii::app()->user->name . ')', array('site/logout')); ?>
                </div>
            </div>
            <div class="row-fluid span12">
                <?php
                $topMenuItems = array();
                foreach ($roots as $menuNode) {
                    $topMenuItems[] = array('label' => $menuNode->label,
                        'url' => 'javascript:;',
                       // 'active'=>true,
                        'linkOptions' => array('id' => 'menu' . $menuNode->id, 'www' => 'jjj')
                    );
                }

                $this->widget('bootstrap.widgets.TbMenu', array(
                    'type' => 'tabs', // '', 'tabs', 'pills' (or 'list')
                    'stacked' => false, // whether this is a stacked menu
                    'items' => $topMenuItems,
                    'htmlOptions' => array(
                        'id' => 'topNav', //  这个竟然不管用
                    ),
                    'id' => 'topNav'
                )); ?>
            </div>
        </div>
    </div>

</header>

<div class="container-fluid">
    <div class="row-fluid">
        <div class="span2">
            <div class="leftmenu">
                <div id='left_side_nav'>
                    <?php $this->renderPartial('_leftSide', array('descendants' => $descendants)); ?>
                </div>

            </div>
        </div>
        <div class="span10">
            <?php
            $this->widget('my.widgets.iframeAutoHeight.IFrameAutoHeight', array(
                    //'debug' => false
                )
            );
            ?>
            <iframe src="http://www.g.cn/" name="contentFrame" id="contentFrame" frameborder="0"
                    width="100%"
                    height="900px"/>
            <!--Body content-->
        </div>
    </div>
</div>

<footer class=footer>
    <div class="row">
        <div class="span12">
            <p class="pull-left">&copy; <?php echo date('Y'); ?> - <?php echo Yii::app()->name ; ?>, All rights reserved.</p>
            <p class="pull-right"><a href="#">Terms of Use</a> &nbsp;|&nbsp; <a href="#">Privacy Policy</a></p>
        </div>
    </div>
</footer>

</body>
</html>