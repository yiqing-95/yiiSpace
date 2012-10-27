<!DOCTYPE html>

<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!-->
<html lang="en"> <!--<![endif]-->
<head>
    <meta charset="utf-8"
    /
    <!-- Set the viewport width to device width for mobile -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

    <!-- Set the viewport width to device width for mobile -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <!-- For third-generation iPad with high-resolution Retina display: -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144"
          href="<?php echo PublicAssets::instance()->url('yii_found3'); ?>/images/favicons/apple-touch-icon-144x144-precomposed.png">
    <!-- For iPhone with high-resolution Retina display: -->
    <link rel="apple-touch-icon-precomposed" sizes="114x114"
          href="<?php echo PublicAssets::instance()->url('yii_found3'); ?>/images/favicons/apple-touch-icon-114x114-precomposed.png">
    <!-- For first- and second-generation iPad: -->
    <link rel="apple-touch-icon-precomposed" sizes="72x72"
          href="<?php echo PublicAssets::instance()->url('yii_found3'); ?>/images/favicons/apple-touch-icon-72x72-precomposed.png">
    <!-- For non-Retina iPhone, iPod Touch, and Android 2.1+ devices: -->
    <link rel="apple-touch-icon-precomposed"
          href="<?php echo PublicAssets::instance()->url('yii_found3'); ?>/images/favicons/apple-touch-icon-precomposed.png">
    <!-- For non-Retina iPhone, iPod Touch, and Android 2.1+ devices: -->
    <link rel="icon" href="<?php echo PublicAssets::instance()->url('yii_found3'); ?>/images/favicons/favicon.ico"
          type="image/x-icon"/>
    <meta name="keywords"
          content="foundation, design, frameworks, framework, css framework, html framework, html5, css3, SASS, SCSS, compass, responsive, design"/>
    <meta name="description"
          content="Documentation for Foundation by ZURB. Foundation is the most advanced responsive front-end framework in the world."/>
    <meta name="author" content="ZURB, inc. ZURB network also includes zurb.com"/>
    <meta name="copyright" content="ZURB, inc. Copyright (c) 2012"/>

    <?php Yii::app()->clientScript->registerCssFile(PublicAssets::instance()->url('yii_found3') . "/css/zurb.mega-drop.css"); ?>
    <?php Yii::app()->clientScript->registerCssFile(PublicAssets::instance()->url('yii_found3') . "/css/foundation.top-bar.css"); ?>
    <title><?php echo Yii::app()->name; ?> : <?php echo $this->pageTitle; ?></title>
    <link rel="icon" type="image/ico" href="<?php echo PublicAssets::instance()->url('yii_found3'); ?>/favicon.ico">

    <!-- Included CSS Files -->
    <link rel="stylesheet" href="<?php echo PublicAssets::instance()->url('yii_found3'); ?>/css/presentation.css">

    <!--[if lt IE 9]>
    <link rel="stylesheet" href="../stylesheets/ie.css">
    <![endif]-->

    <!-- IE Fix for HTML5 Tags -->
    <!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <script type="text/javascript">

        var _gaq = _gaq || [];
        _gaq.push(['_setAccount', 'UA-6958144-5']);
        _gaq.push(['_setDomainName', 'oakwebdev.com']);
        _gaq.push(['_trackPageview']);

        (function () {
            var ga = document.createElement('script');
            ga.type = 'text/javascript';
            ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0];
            s.parentNode.insertBefore(ga, s);
        })();

    </script>
</head>
<body>
<!-- ZURBar -->
<div class="container top-bar home-border">
    <div class="attached">
        <div class="name">
            <span><a
                href="<?php echo Yii::app()->request->getBaseUrl(); ?>"><?php echo CHtml::encode(Yii::app()->name); ?></a></span>
        </div>
        <?php

        $this->widget('zii.widgets.CMenu', array(
        'htmlOptions' => array(
            'class' => 'right',
        ),
        'items' => array(
            array('label' => 'Home', 'url' => array('/site/index')),
            array('url'  => array('/site/login'), 'label' =>'login', 'visible' => Yii::app()->user->isGuest),
            array('label' => 'About', 'url' => array('/site/page', 'view' => 'about')),
            array('label' => 'Contact', 'url' => array('/site/contact')),
            array( 'url' => array('/site/logout'), 'label' =>"Logout" . ' (' . Yii::app()->user->name . ')', 'visible' => !Yii::app()->user->isGuest),
        ),
    )); ?>
    </div>
</div>
<!-- /ZURBar -->
<header id="header" class="row">
    <div class="eight columns">
        <h1><a href="<?php echo Yii::app()->createUrl("site/index"); ?>">welcome to YiiSpace</a></h1>
        <h4 class="subheader">want to become a great sns site build with Yii.</h4>

        <div class="show-for-small menu-action">
            <a class='sidebar-button small secondary button' id='sidebarButton' href="#sidebar">

            </a>
        </div>
    </div>
</header>

<div class="row">
   <?php echo $content; ?>
</div>

<div class="row">
    <div class="twelve columns">
        <hr/>
    </div>
</div>

<!-- Das Footer -->
<footer class="row">
    <section class="three columns">
        <h6>
            <strong>Made by AsgarothBelem</strong>
        </h6>

        <p>Yii Foundation is made by <a href="http://twitter.com/#!/asgarothbelem">AsgarothBelem</a>, a Web programmer
            and developer located in Bogota, Colombia.</p>
    </section>
    <section class="three columns">
        <h6><strong>Made by ZURB</strong></h6>

        <p>Foundation is made by <a href="http://www.zurb.com/">ZURB</a>, a product design agency in Campbell,
            California. We've put more than 14 years of experience building web products, services and websites into
            this framework. <a href="../about.php">Foundation Info and Goodies &rarr;</a></p>
    </section>

    <section class="three columns">
        <h6><strong>Using Foundation?</strong></h6>

        <p>Let us know how you're using Foundation and we might feature you as an example! <a
            href="mailto:foundation@zurb.com?subject=I'm%20using%20Foundation">Get in touch &rarr;</a></p>
    </section>

    <section class="three columns">
        <h6><strong>Need some help?</strong></h6>

        <p>For answers or help visit the <a href="support.php">Support page</a>.</p>
        <ul class="link-list">
            <li>
                <a href="http://www.facebook.com/ZURB" target="_blank"><span class="glyph social">d</span></a>
            </li>
            <li>
                <a href="https://plusone.google.com/_/+1/confirm?url=http://foundation.zurb.com&title=Foundation%20from%20ZURB"
                   target="_blank"><span class="glyph social">l</span></a>
            </li>
            <li>
                <a href="http://www.twitter.com/foundationzurb" target="_blank"><span class="glyph social">e</span></a>
            </li>
        </ul>
    </section>
</footer>

<div id="copyright">
    <div class="row">
        <div class="four columns">
            <p>Site content &copy; 2012 ZURB, inc.</p>
        </div>

        <div class="eight columns">
            <ul class="link-list right">
                <li>
                    <a href="<?php echo Yii::app()->createUrl('site/index'); ?>">
                        Home
                    </a>
                </li>
                <li class="download">
                    <a class="small blue nice button src-download"
                       href="http://www.yiiframework.com/extension/foundation3/">
                        Download
                    </a>
                </li>
                <li>
                    <a href="http://twitter.com/#!/asgarothbelem">@AsgarothBelem</a>
                </li>
                <li>
                    <a href="<?php echo Yii::app()->createUrl("site/index"); ?>">Documentation</a>
                </li>
                <li>
                    <a href="https://github.com/Asgaroth/foundation">Github</a>
                </li>
            </ul>
        </div>
    </div>
</div>


<?php Yii::app()->clientScript->registerScriptFile(PublicAssets::instance()->url('yii_found3') . "/js/jquery.offcanvas.js", CClientScript::POS_END); ?>
<?php
$this->widget('ext.scrolltop.ScrollTop',
    array(
        //Default values
        'fadeTransitionStart' => 10,
        'fadeTransitionEnd' => 200,
        'speed' => 'slow'
    ));
?>
</body>
</html>