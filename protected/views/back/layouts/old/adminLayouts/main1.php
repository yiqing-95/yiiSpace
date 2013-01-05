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
    </style>


    <link href="<?php echo $assetsUrl; ?>/css/bootstrap-responsive.css" rel="stylesheet">
    <link href="<?php echo $assetsUrl; ?>/css/charisma-app.css" rel="stylesheet">
    <link href="<?php echo $assetsUrl; ?>/css/jquery-ui-1.8.21.custom.css" rel="stylesheet">
    <link href='<?php echo $assetsUrl; ?>/css/fullcalendar.css' rel='stylesheet'>
    <link href='<?php echo $assetsUrl; ?>/css/fullcalendar.print.css' rel='stylesheet' media='print'>
    <link href='<?php echo $assetsUrl; ?>/css/chosen.css' rel='stylesheet'>
    <link href='<?php echo $assetsUrl; ?>/css/uniform.default.css' rel='stylesheet'>
    <link href='<?php echo $assetsUrl; ?>/css/colorbox.css' rel='stylesheet'>
    <link href='<?php echo $assetsUrl; ?>/css/jquery.cleditor.css' rel='stylesheet'>
    <link href='<?php echo $assetsUrl; ?>/css/jquery.noty.css' rel='stylesheet'>
    <link href='<?php echo $assetsUrl; ?>/css/noty_theme_default.css' rel='stylesheet'>
    <link href='<?php echo $assetsUrl; ?>/css/elfinder.min.css' rel='stylesheet'>
    <link href='<?php echo $assetsUrl; ?>/css/elfinder.theme.css' rel='stylesheet'>
    <link href='<?php echo $assetsUrl; ?>/css/jquery.iphone.toggle.css' rel='stylesheet'>
    <link href='<?php echo $assetsUrl; ?>/css/opa-icons.css' rel='stylesheet'>
    <link href='<?php echo $assetsUrl; ?>/css/uploadify.css' rel='stylesheet'>

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
            });

            var activeTopMenuId = "<?php if(($activeTopMenuId = user()->getState('activeTopMenuId',false))!== false) echo $activeTopMenuId; ?>";
            if(activeTopMenuId.length > 0){
              //  alert(activeTopMenuId);
                $("#menu"+activeTopMenuId).click();
            }
        });

    </script>

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
            <a class="brand" href="index.html"> <img alt="Charisma Logo" src="img/logo20.png"/>
                <span>Charisma</span></a>

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

    <!--    这里渲染顶级菜单：-->
    <?php
    $topRoot = AdminMenu::model()->find('group_code=:group_code', array(':group_code' => 'sys_admin_menu_root'));
    $roots = $topRoot->children()->findAll();
    $descendants = $topRoot->descendants()->findAll();
    ?>

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


    <div class="row-fluid">
        <?php if (!isset($no_visible_elements) || !$no_visible_elements) { ?>

        <!-- left menu starts -->
        <div class="span2 main-menu-span">
            <div class="well nav-collapse sidebar-nav" id="sidebar_nav">
                <?php /*
                <ul class="nav nav-tabs nav-stacked main-menu">
                    <li class="nav-header hidden-tablet">Main</li>
                    <li>
                        <a class="ajax-link" href="index.html">
                            <i class="icon-home"></i>
                            <span class="hidden-tablet"> Dashboard</span>
                        </a>
                    </li>
                    <li><a class="ajax-link" href="ui.html">
                        <i class="icon-eye-open"></i>
                        <span class="hidden-tablet"> UI Features  </span>
                    </a>
                    </li>
                    <li><a class="ajax-link" href="form.html"><i class="icon-edit"></i><span class="hidden-tablet"> Forms</span></a>
                    </li>
                    <li><a class="ajax-link" href="chart.html"><i class="icon-list-alt"></i><span class="hidden-tablet"> Charts</span></a>
                    </li>
                    <li><a class="ajax-link" href="typography.html"><i class="icon-font"></i><span
                        class="hidden-tablet"> Typography</span></a></li>
                    <li><a class="ajax-link" href="gallery.html"><i class="icon-picture"></i><span
                        class="hidden-tablet"> Gallery</span></a></li>
                    <li class="nav-header hidden-tablet">Sample Section</li>
                    <li><a class="ajax-link" href="table.html"><i class="icon-align-justify"></i><span
                        class="hidden-tablet"> Tables</span></a></li>
                    <li><a class="ajax-link" href="calendar.html"><i class="icon-calendar"></i><span
                        class="hidden-tablet"> Calendar</span></a></li>
                    <li><a class="ajax-link" href="grid.html"><i class="icon-th"></i><span
                        class="hidden-tablet"> Grid</span></a></li>
                    <li><a class="ajax-link" href="file-manager.html"><i class="icon-folder-open"></i><span
                        class="hidden-tablet"> File Manager</span></a></li>
                    <li><a href="tour.html"><i class="icon-globe"></i><span class="hidden-tablet"> Tour</span></a></li>
                    <li><a class="ajax-link" href="icon.html"><i class="icon-star"></i><span class="hidden-tablet"> Icons</span></a>
                    </li>
                    <li><a href="error.html"><i class="icon-ban-circle"></i><span
                        class="hidden-tablet"> Error Page</span></a></li>
                    <li><a href="login.html"><i class="icon-lock"></i><span class="hidden-tablet"> Login Page</span></a>
                    </li>
                    <li>
                        <a href="login.html"><i class="icon-lock"></i><span class="hidden-tablet"> testNestedLinks</span></a>
                        <ul>
                            <li><a href="login.html"><i class="icon-lock"></i><span class="hidden-tablet"> Login Page</span></a>
                            </li>
                            <li><a href="login.html"><i class="icon-lock"></i><span class="hidden-tablet"> Login Page</span></a>
                            </li>
                            <li><a href="login.html"><i class="icon-lock"></i><span class="hidden-tablet"> Login Page</span></a>
                            </li>
                        </ul>
                    </li>
                </ul> */ ?>



                <label id="for-is-ajax" class="hidden-tablet" for="is-ajax"><input id="is-ajax" type="checkbox"> Ajax on
                    menu</label>
            </div>
            <!--/.well -->

            <div id='left_side_nav' style="display: none;">
                <?php
                $level = 0;

                foreach ($descendants as $n => $node) {
                    if ($node->level == $level) {
                        echo CHtml::closeTag('li') . "\n";
                    } else if ($node->level > $level) {
                        if($node->level == 3){
                            echo CHtml::openTag('ul', array('class' =>'nav nav-tabs nav-stacked main-menu')) . "\n";
                         }else{
                            echo CHtml::openTag('ul', array('level' => $node->level)) . "\n";
                        }

                    } else {
                        echo CHtml::closeTag('li') . "\n";
                        for ($i = $level - $node->level; $i; $i--) {
                            echo CHtml::closeTag('ul') . "\n";
                            echo CHtml::closeTag('li') . "\n";
                        }
                    }
                    if($node->level == 2){
                        echo CHtml::openTag('li', array('id' => '_menu' . $node->id, 'level' => $node->level,'class'=>'nav-header hidden-tablet'));
                    }else{
                        echo CHtml::openTag('li', array('id' => '_menu' . $node->id, 'level' => $node->level));
                    }
                    if($node->level == 2){
                        echo CHtml::encode($node->label);
                    }else{
                        //顶级菜单不显示
                        echo CHtml::link(CHtml::encode($node->label), $node->calcUrl(), array('id' => $node->id));
                    }


                    $level = $node->level;
                }

                for ($i = $level; $i; $i--) {
                    echo CHtml::closeTag('li') . "\n";
                    echo CHtml::closeTag('ul') . "\n";
                }
                ?>
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

        <?php echo $content; ?>
        ========================================================================================================================

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

        <p class="pull-right">Powered by: <a href="http://usman.it/free-responsive-admin-template">Charisma</a></p>
    </footer>
    <?php } ?>

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
<script src="<?php echo $assetsUrl; ?>/js/bootstrap-alert.js"></script>
<!-- modal / dialog library -->
<script src="<?php echo $assetsUrl; ?>/js/bootstrap-modal.js"></script>
<!-- custom dropdown library -->
<script src="<?php echo $assetsUrl; ?>/js/bootstrap-dropdown.js"></script>
<!-- scrolspy library -->
<script src="<?php echo $assetsUrl; ?>/js/bootstrap-scrollspy.js"></script>
<!-- library for creating tabs -->
<script src="<?php echo $assetsUrl; ?>/js/bootstrap-tab.js"></script>
<!-- library for advanced tooltip -->
<script src="<?php echo $assetsUrl; ?>/js/bootstrap-tooltip.js"></script>
<!-- popover effect library -->
<script src="<?php echo $assetsUrl; ?>/js/bootstrap-popover.js"></script>
<!-- button enhancer library -->
<script src="<?php echo $assetsUrl; ?>/js/bootstrap-button.js"></script>
<!-- accordion library (optional, not used in demo) -->
<script src="<?php echo $assetsUrl; ?>/js/bootstrap-collapse.js"></script>
<!-- carousel slideshow library (optional, not used in demo) -->
<script src="<?php echo $assetsUrl; ?>/js/bootstrap-carousel.js"></script>
<!-- autocomplete library -->
<script src="<?php echo $assetsUrl; ?>/js/bootstrap-typeahead.js"></script>
<!-- tour library -->
<script src="<?php echo $assetsUrl; ?>/js/bootstrap-tour.js"></script>
<!-- library for cookie management -->
<script src="<?php echo $assetsUrl; ?>/js/jquery.cookie.js"></script>
<!-- calander plugin -->
<script src='<?php echo $assetsUrl; ?>/js/fullcalendar.min.js'></script>
<!-- data table plugin -->
<script src='<?php echo $assetsUrl; ?>/js/jquery.dataTables.min.js'></script>

<!-- chart libraries start -->
<script src="<?php echo $assetsUrl; ?>/js/excanvas.js"></script>
<script src="<?php echo $assetsUrl; ?>/js/jquery.flot.min.js"></script>
<script src="<?php echo $assetsUrl; ?>/js/jquery.flot.pie.min.js"></script>
<script src="<?php echo $assetsUrl; ?>/js/jquery.flot.stack.js"></script>
<script src="<?php echo $assetsUrl; ?>/js/jquery.flot.resize.min.js"></script>
<!-- chart libraries end -->

<!-- select or dropdown enhancer -->
<script src="<?php echo $assetsUrl; ?>/js/jquery.chosen.min.js"></script>
<!-- checkbox, radio, and file input styler -->
<script src="<?php echo $assetsUrl; ?>/js/jquery.uniform.min.js"></script>
<!-- plugin for gallery image view -->
<script src="<?php echo $assetsUrl; ?>/js/jquery.colorbox.min.js"></script>
<!-- rich text editor library -->
<script src="<?php echo $assetsUrl; ?>/js/jquery.cleditor.min.js"></script>
<!-- notification plugin -->
<script src="<?php echo $assetsUrl; ?>/js/jquery.noty.js"></script>
<!-- file manager library -->
<script src="<?php echo $assetsUrl; ?>/js/jquery.elfinder.min.js"></script>
<!-- star rating plugin -->
<script src="<?php echo $assetsUrl; ?>/js/jquery.raty.min.js"></script>
<!-- for iOS style toggle switch -->
<script src="<?php echo $assetsUrl; ?>/js/jquery.iphone.toggle.js"></script>
<!-- autogrowing textarea plugin -->
<script src="<?php echo $assetsUrl; ?>/js/jquery.autogrow-textarea.js"></script>
<!-- multiple file upload plugin -->
<script src="<?php echo $assetsUrl; ?>/js/jquery.uploadify-3.1.min.js"></script>
<!-- history.js for cross-browser state change on ajax -->
<script src="<?php echo $assetsUrl; ?>/js/jquery.history.js"></script>
<!-- application script for Charisma demo -->
<script src="<?php echo $assetsUrl; ?>/js/charisma.js"></script>

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