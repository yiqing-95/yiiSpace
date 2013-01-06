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

    <?php
    /**
     * @see http://www.yiiframework.com/wiki/152/cookie-management-in-yii
     */
    $currentTheme = isset(Yii::app()->request->cookies['current_theme']) ?
        Yii::app()->request->cookies['current_theme']->value : user()->getState('currentTheme','cerulean');
    ?>
    <!-- The styles -->
    <link id="bs-css" href="<?php echo "{$assetsUrl}/css/bootstrap-{$currentTheme}.css"; ?>" rel="stylesheet"
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

    <link href='<?php echo $assetsUrl; ?>/css/jquery.iphone.toggle.css' rel='stylesheet'>


    <!-- The HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- The fav icon -->
    <link rel="shortcut icon" href="<?php echo $assetsUrl; ?>/img/favicon.ico">

</head>

<body>
<div class="container-fluid">
    <?php // echo user()->getState('currentTheme','cerulean'); ?>
    <div class="row-fluid">
        <?php echo $content; ?>
    </div>
    <!--/fluid-row-->

</div>
<!--/.fluid-container-->

<!-- external javascript
    ================================================== -->
<!-- Placed at the end of the document so the pages load faster -->

<!-- chart libraries start -->
<script src="<?php echo $assetsUrl; ?>/js/excanvas.js"></script>
<script src="<?php echo $assetsUrl; ?>/js/jquery.flot.min.js"></script>
<script src="<?php echo $assetsUrl; ?>/js/jquery.flot.pie.min.js"></script>
<script src="<?php echo $assetsUrl; ?>/js/jquery.flot.stack.js"></script>
<script src="<?php echo $assetsUrl; ?>/js/jquery.flot.resize.min.js"></script>
<!-- chart libraries end -->

<!--<script src="--><?php //echo $assetsUrl; ?><!--/js/charisma.js"></script>-->
<script src="<?php echo PublicAssets::url(); ?>/js/backend/iframe.js"></script>

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

<script type="text/javascript">
    $(function(){
        // set the current theme url same as the parent's
        if($('#bs-css').attr('href') !== parent.getCurrentThemeUrl()){
            // $('#bs-css').attr('href',parent.getCurrentThemeUrl());
        }
        $(document).resize(function(){
            parent.risizeIframe();
        });
    });
    function switch_theme(theme_name)
    {
        //$('#bs-css').attr('href','css/bootstrap-'+theme_name+'.css');
        var themeTplUrl = $('#bs-css').attr("theme_tpl_url");
        $('#bs-css').attr('href',themeTplUrl.replace('{{theme_name}}',theme_name));
    }
</script>
</body>
</html>