<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">



    <?php  $this->widget('my.widgets.CascadeFr.CascadeFr'); ?>
    <?php PublicAssets::registerCssFile('css/site.css'); ?>

    <?php
    //  aliceUi  看看跟cascadeFramework 冲突不？
    // Yii::import('foy.widgets.alice.*');
    //     AliceUI::registerCoreCss() ;

    $seaJsModuleUrl = SeaJs::getSeaJsModuleUrl();

    ?>
    <script src="<?php echo $seaJsModuleUrl.('/seajs/seajs/2.1.1/sea.js') ?>" id="seajsnode"></script>
    <!--
      直接用script 的方式加载jquery 不然加载顺序无法保证 这样jquery常规插件就可以用了
      @see https://github.com/seajs/seajs/issues/996
    -->
    <script src="<?php echo $seaJsModuleUrl.('/jquery/jquery/1.10.1/jquery.js') ?>" ></script>


    <script type="text/javascript">
        // 配置 jquery 并放入预加载项中
        seajs.config({
            alias: {
                'jquery': 'jquery/jquery/1.10.1/jquery.js',
                '$': 'jquery/jquery/1.10.1/jquery.js'
            },
            preload: ["jquery"]
        });
        seajs.use(['jquery'], function ($) {
            window.jQuery  =   window.$ = $ ;
        });

        /*
         seajs.use('jquery', function(jquery) {
         });
         // use 下才加载？
         define(function(require, exports, module) {
         var $ = require('jquery');
         //=> 加载的是 http://path/to/base/jquery/jquery/1.10.1/jquery.js

         });
         */
    </script>



            <title><?php echo $this->getPageTitle(); ?></title>
    <?php
    /*
     * 蛋疼的竟然冲突！
     *
    Yii::app()->getController()->widget('ext.seo.widgets.SeoHead',
        array(
            'httpEquivs'=>array(
                'Content-Type'=>'text/html; charset=utf-8',
                'Content-Language'=> Yii::app()->getLanguage(),
            ),
            'defaultDescription'=>'YiiSpace is  sns project and   opensource powerd by Yii framework .',
            'defaultKeywords'=>'YiiSpace ,yiqing-95 , yiqing ',
        ));
    */
    ?>
    <?php
    // 禁用yii自带的jquery
    $cs=Yii::app()->clientScript;

    $cs->scriptMap=array(
        'jquery.js'=>false,  // debug mode
        'jquery.min.js'=>false, // disable debug mode
    );

    ?>

    <meta name="description" content="Professional Frontend framework that makes building websites easier than ever."/>
    <!--    <link rel="shortcut icon" href="../vendor/assets/img/favicon.ico" type="image/x-icon" />-->
    <meta name="viewport" content="width=device-width" />

</head>


<body class="narrow">
<div class="site-center">
    <div class="col width-fill background-orange">
        dododoodo

    </div>
    <div class="col">
        <div class="col width-fit mobile-sizefit">
            <div class="cell">
                <div class="col">
                    <div class="col width-6of9">
                        <a href="<?php echo $this->createUrl('/site/index'); ?>" class="logo">
                            <?php echo Yii::app()->name; ?>
                        </a>
                    </div>

                    <div class="col width-fill ">
                        <div class="col">
                            <div class="pipes">
                                <?php
                                if(UserHelper::getIsLoginUser()) {
                                    $items = array(
                                        array('label' => '个人空间', 'url' =>UserHelper::getUserSpaceUrl(Yii::app()->user->getId())),
                                        array('label' => '个人中心', 'url' =>UserHelper::getUserCenterUrl()),
                                        array('label' => 'Logout (' . Yii::app()->user->name . ')', 'url' => array('/site/logout'))
                                    );
                                }else{
                                    $items = array(
                                        array('label' => 'Home', 'url' => array('/site/index')),
                                        array('label' => 'About', 'url' => array('/site/page', 'view' => 'about')),
                                        array('label' => 'Contact', 'url' => array('/site/contact')),
                                        array('label' => 'Login', 'url' => array('/site/login'), 'visible' => Yii::app()->user->isGuest),
                                    );
                                }
                                $this->widget('zii.widgets.CMenu', array(
                                    'htmlOptions' => array('class' => 'nav'),
                                    'linkLabelWrapper' => 'span',
                                    'activeCssClass' => 'current',
                                    'items'=>$items,
                                )); ?>
                            </div>
                        </div>
                        <div class="col">
                            <div class="pipes">
                                <ul class="nav">
                                    <li class="hovered-item"><a href="#" class="child-of-hovered-item">Normal item</a>
                                    </li>
                                    <li class="active"><a href="#" class="">Active item</a></li>
                                    <li class="disabled">Disabled item</li>
                                </ul>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
<div class="site-nav col width-fill background-blue">
    <div class="" style="margin-left: 80px">
        <ul class="nav">
            <li class="">
                <a href="#" class="">Normal item</a>
            </li>
            <li class="">
                <a href="<?php echo $this->createUrl('/blog');?>">博文</a>
            </li>

            <li>
                <a href="<?php echo Yii::app()->createUrl('/group') ?>">群组</a>
            </li>

            <li>
                <a href="<?php echo Yii::app()->createUrl('/search') ?>">搜索</a>
            </li>
        </ul>
    </div>

</div>


<div class="site-center">
    <?php echo $content; ?>
</div>


<div class="divider" style="background-color: red;height: 5px;border-bottom: 2px solid #2A333C;margin: 10px 0 ;"></div>
<div class="site-center">
    <div class="site-footer">
        <div class="cell">
            <div id="sociallogos">
                <a href="https://twitter.com/cascadecss"><span class="icon icon-32 icon-twitter"></span></a>
                <a href="http://www.facebook.com/cascadeframework"><span class="icon icon-32 icon-facebook-sign"></span></a>
                <a href="https://github.com/CascadeFramework"><span class="icon icon-32 icon-github"></span></a>

                <div class="col">
                    &#169; 2013, <a href="https://twitter.com/johnslegers">John Slegers</a>
                </div>
            </div>
            <a href="index.html" class="powered-by"></a>
        </div>
    </div>
</div>
<div class="col">
    <div class="cell" style="text-align: center ;">
        <?php $this->widget('application.widgets.FriendLinksWidget'); ?>
    </div>
</div>

</body>
</html>
