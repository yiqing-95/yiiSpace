<!DOCTYPE html>
<html lang="en">
<head>
    <!--
         Charisma v1.0.0

         Copyright 2012 Muhammad Usman
         Licensed under the Apache License v2.0
         http://www.apache.org/licenses/LICENSE-2.0

         http://usman.it
         http://twitter.com/halalit_usman

     -->

    <?php $assetsUrl = PublicAssets::url('back'); ?>

    <meta charset="utf-8">
    <title>Free HTML5 Bootstrap Admin Template</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Charisma, a fully featured, responsive, HTML5, Bootstrap admin template.">
    <meta name="author" content="Muhammad Usman">

    <!-- The styles -->
    <link id="bs-css" href="<?php echo $assetsUrl; ?>/css/bootstrap-cerulean.css" rel="stylesheet"
          theme_tpl_url="<?php echo $assetsUrl; ?>/css/bootstrap-{{theme_name}}.css">
    <style type="text/css">
        body {
            padding-bottom: 40px;
        }

        .sidebar-nav {
            padding: 9px 0;
        }

        .activeTreeMenu{
            list-style-type: none;
        }
    </style>


    <link href="<?php echo $assetsUrl; ?>/css/bootstrap-responsive.css" rel="stylesheet">
    <link href="<?php echo $assetsUrl; ?>/css/charisma-app.css" rel="stylesheet">
    <link href="<?php echo $assetsUrl; ?>/css/jquery-ui-1.8.21.custom.css" rel="stylesheet">
    <link href='<?php echo $assetsUrl; ?>/css/jquery.noty.css' rel='stylesheet'>
    <link href='<?php echo $assetsUrl; ?>/css/noty_theme_default.css' rel='stylesheet'>

    <!-- The HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- The fav icon -->
    <link rel="shortcut icon" href="<?php echo $assetsUrl; ?>/img/favicon.ico">
    <?php
    cs()->registerCoreScript('jquery');
    PublicAssets::registerScriptFile('js/currentActive.js'); ?>

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

                $("#sidebar_nav").prepend($(".activeTreeMenu"));

                $("#contentFrame").attr('src', $("#" + childrenId).find('a:first').attr('href'));
            });

        });

        function refresh() {
            parent.contentFrame.location.reload(true);
        }

        /**
         *
         * @return {*}
         */
        function  getCurrentThemeUrl(){
          return  $('#bs-css').attr('href');
        }

        function syncIFrameTheme(themeName){
            /**
             *
             *  var $f = $("#myIFrame");
             * var fd = $f[0].document || $f[0].contentWindow.document; // document of iframe
             * fd.MyFunction();  // run function
             */
            var el = document.getElementById('contentFrame');

            getIframeWindow(el).switch_theme(themeName);
        }

        /**
         * @see http://stackoverflow.com/questions/251420/invoking-javascript-in-iframe-from-parent-page
         * @param iframe_object
         * @return {*}
         */
        function getIframeWindow(iframe_object) {
            var doc;
            if (iframe_object.contentWindow) {
                return iframe_object.contentWindow;
            }
            if (iframe_object.window) {
                return iframe_object.window;
            }
            if (!doc && iframe_object.contentDocument) {
                doc = iframe_object.contentDocument;
            }
            if (!doc && iframe_object.document) {
                doc = iframe_object.document;
            }
            if (doc && doc.defaultView) {
                return doc.defaultView;
            }
            if (doc && doc.parentWindow) {
                return doc.parentWindow;
            }
            return undefined;
        }

        // fire iframe resize when window is resized
        var windowResizeFunction = function (resizeFunction, iframe) {
            $(window).resize(function () {
                console.debug("window resized - firing resizeHeight on iframe");
                resizeFunction(iframe);
            });
        };

        // fire iframe resize when a link is clicked
        var clickResizeFunction = function (resizeFunction, iframe) {
            $('#dummyLink').click(function () {
                console.debug("link clicked - firing resizeHeight on iframe");
                resizeFunction(iframe);
            });
        };
        function risizeIframe(){
            $('#dummyLink').click();
        }

        function setCurrentTheme2session(themeName){
            var url = "<?php echo $this->createUrl('/site/setTheme');?>";
            $.get(url,{"currentTheme":themeName});
        }
    </script>
    <?php
    $this->widget('my.widgets.iframeAutoHeight.IFrameAutoHeight', array(
            'debug' => false,
            'options'=>array(
                'animate'=>false,
                'minHeight'=>400,
                'heightOffset'=>20,
                'triggerFunctions'=>array('js:windowResizeFunction',
                    'js:clickResizeFunction',
                )
            )
        )
    );
    ?>
</head>

<body>
<?php if (!isset($no_visible_elements) || !$no_visible_elements) { ?>
<!-- topbar starts -->
<div class="navbar">
    <div class="navbar-inner">
        <div class="container-fluid">
            <a class="btn btn-navbar" data-toggle="collapse"
               data-target=".top-nav.nav-collapse,.sidebar-nav.nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <a class="brand" href="">
                <img alt="app Logo" src="<?php echo bu('public/images/yii.png');?>"/>
                <span><?php echo Yii::app()->name; ?></span>
            </a>

            <!-- theme selector starts -->
            <div class="btn-group pull-right theme-container">
                <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="icon-tint"></i><span class="hidden-phone"> Change Theme / Skin</span>
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu" id="themes">
                    <li><a data-value="classic" href="#"><i class="icon-blank"></i> Classic</a></li>
                    <li><a data-value="cerulean" href="#"><i class="icon-blank"></i> Cerulean</a></li>
                    <li><a data-value="cyborg" href="#"><i class="icon-blank"></i> Cyborg</a></li>
                    <li><a data-value="redy" href="#"><i class="icon-blank"></i> Redy</a></li>
                    <li><a data-value="journal" href="#"><i class="icon-blank"></i> Journal</a></li>
                    <li><a data-value="simplex" href="#"><i class="icon-blank"></i> Simplex</a></li>
                    <li><a data-value="slate" href="#"><i class="icon-blank"></i> Slate</a></li>
                    <li><a data-value="spacelab" href="#"><i class="icon-blank"></i> Spacelab</a></li>
                    <li><a data-value="united" href="#"><i class="icon-blank"></i> United</a></li>
                </ul>
            </div>
            <!-- theme selector ends -->

            <!-- user dropdown starts -->
            <div class="btn-group pull-right">
                <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="icon-user"></i><span class="hidden-phone"> admin</span>
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="#">Profile</a></li>
                    <li class="divider"></li>
                    <li><a href="login.html">Logout</a></li>
                </ul>
            </div>
            <!-- user dropdown ends -->

            <div class="top-nav nav-collapse">
                <ul class="nav">
                    <li><a href="#">Visit Site</a></li>
                    <li>
                        <form class="navbar-search pull-left">
                            <input placeholder="Search" class="search-query span2" name="query" type="text">
                        </form>
                    </li>
                </ul>
            </div>
            <!--/.nav-collapse -->
        </div>
    </div>
</div>
<!-- topbar ends -->
    <?php } ?>
<div class="container-fluid">

    <div class="row-fluid">
        <div class="span1">
            <?php // echo CHtml::image(bu('public/images/yii.png')); ?>
        </div>
        <div class="span11">
            <div class="row-fluid">
                <div class="span4 pull-right">
                    <a href="<?php echo $this->createUrl('/menuBuilder'); ?>" target="_blank" >menuBuilder</a>
                      |
                    <a title="frontend" href="<?php echo abu('index.php'); ?>" target="_blank">frontEnd</a> |
                    <a title="refresh" href="javascript:;" onclick='refresh()'>refresh</a> |
                    <?php echo CHtml::link("logout(" . Yii::app()->user->name . ')', array('site/logout')); ?>
                </div>
            </div>
            <!--    这里渲染顶级菜单：-->

            <div class="row-fluid ">
                <div class="span2">

                </div>
                <div class="span9">
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
                        'type' => 'pills', // '', 'tabs', 'pills' (or 'list')
                        'stacked' => false, // whether this is a stacked menu
                        'items' => $topMenuItems,
                        'htmlOptions' => array(
                            'id' => 'topNav', //  这个竟然不管用
                            'class' => 'pull-left',
                        ),
                        'id' => 'topNav'
                    )); ?>
                </div>

            </div>

            <!--    渲染顶级菜单 end !-->

        </div>
    </div>


    <div class="row-fluid">
        <?php if (!isset($no_visible_elements) || !$no_visible_elements) { ?>

        <!-- left menu starts -->
        <div class="span2 main-menu-span">
            <div class="well nav-collapse sidebar-nav" id="sidebar_nav">

            </div>
            <!--/.well -->

            <div id='left_side_nav' style="display: none;">
                <?php $this->renderPartial('_leftSide', array('descendants' => $descendants)); ?>
            </div>
        </div><!--/span-->
        <!-- left menu ends -->

        <noscript>
            <div class="alert alert-block span10">
                <h4 class="alert-heading">Warning!</h4>

                <p>You need to have <a href="http://en.wikipedia.org/wiki/JavaScript" target="_blank">JavaScript</a>
                    enabled to use this site.</p>
            </div>
        </noscript>


			<div id="content" class="span10">
			<!-- content starts -->
        <?php } ?>
        <iframe src="<?php echo $this->createUrl('/site/page',array('view'=>'about'));?>" class="auto-height span12" scrolling="no" frameborder="0" name="contentFrame" id="contentFrame"></iframe>

        <?php if (!isset($no_visible_elements) || !$no_visible_elements) { ?>
        <!-- content ends -->
			</div><!--/#content.span10-->
        <?php } ?>
    </div>
    <!--/fluid-row-->
    <?php if (!isset($no_visible_elements) || !$no_visible_elements) { ?>

    <hr>

    <div class="modal hide fade" id="myModal">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <h3>Settings</h3>
        </div>
        <div class="modal-body">
            <p>Here settings can be configured...</p>
        </div>
        <div class="modal-footer">
            <a href="#" class="btn" data-dismiss="modal">Close</a>
            <a href="#" class="btn btn-primary">Save changes</a>
        </div>
    </div>

    <footer>
        <p class="pull-left">&copy; <a href="http://usman.it" target="_blank">Muhammad Usman</a> <?php echo date('Y') ?>
        </p>

        <p class="pull-right">Powered by: <a href="http://usman.it/free-responsive-admin-template">Charisma</a>
            Powered by: <a href="http://usman.it/free-responsive-admin-template">yiqing</a>
        </p>
    </footer>
    <?php } ?>

<!--    for resize the iframe -->
 <a href="javascript:;" class="dummy" id="dummyLink"></a>
    <!--    for resize the iframe /-->
</div>
<!--/.fluid-container-->

<!-- external javascript
    ================================================== -->
<!-- Placed at the end of the document so the pages load faster -->

<!-- jQuery -->
<!--<script src="--><?php //echo $assetsUrl; ?><!--/js/jquery-1.7.2.min.js"></script>-->
<!-- jQuery UI -->
<script src="<?php echo $assetsUrl; ?>/js/jquery-ui-1.8.21.custom.min.js"></script>
<!-- transition / effect library -->
<script src="<?php echo $assetsUrl; ?>/js/bootstrap-transition.js"></script>
<!-- alert enhancer library -->
<!-- custom dropdown library -->
<script src="<?php echo $assetsUrl; ?>/js/bootstrap-dropdown.js"></script>
<!-- scrolspy library -->
<script src="<?php echo $assetsUrl; ?>/js/bootstrap-popover.js"></script>
<!-- autocomplete library -->
<script src="<?php echo $assetsUrl; ?>/js/bootstrap-typeahead.js"></script>
<!-- library for cookie management -->
<script src="<?php echo $assetsUrl; ?>/js/jquery.cookie.js"></script>
<!-- for iOS style toggle switch -->
<script src="<?php echo $assetsUrl; ?>/js/jquery.iphone.toggle.js"></script>
<!-- history.js for cross-browser state change on ajax -->
<script src="<?php echo $assetsUrl; ?>/js/jquery.history.js"></script>
<!-- application script for siteIndex -->
<script src="<?php echo PublicAssets::url(); ?>/js/backend/siteIndex.js"></script>

<?php //Google Analytics code for tracking my demo site, you can remove this.
if ($_SERVER['HTTP_HOST'] == 'usman.it') {
    ?>
<script>
    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-26532312-1']);
    _gaq.push(['_trackPageview']);
    (function () {
        var ga = document.createElement('script');
        ga.type = 'text/javascript';
        ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(ga);
    })();
</script>
    <?php } ?>

</body>
</html>