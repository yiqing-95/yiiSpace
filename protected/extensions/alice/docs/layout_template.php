<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
<!--     集成seajs 的示例-->
    <?php
       // 注册aliceUi需要的css（就一个哦！）
       Yii::app()->alice->registerCoreCss();
    ?>
    <script src="<?php echo bu('public/sea-modules/seajs/seajs/2.1.1/sea.js') ?>" id="seajsnode"></script>
    <!--
      直接用script 的方式加载jquery 不然加载顺序无法保证 这样jquery常规插件就可以用了
      @see https://github.com/seajs/seajs/issues/996
    -->
    <script src="<?php echo bu('public/sea-modules/jquery/jquery/1.10.1/jquery.js') ?>" ></script>

    <script type="text/javascript">
        // 配置 jquery 并放入预加载项中
        seajs.config({
            alias: {
                'jquery': 'jquery/jquery/1.10.1/jquery.js',
                '$': 'jquery/jquery/1.10.1/jquery.js'
            },
            preload: ["jquery"]
        });

    </script>

    <?php
    // 禁用yii自带的jquery
    $cs=Yii::app()->clientScript;
    $cs->scriptMap=array(
        'jquery.js'=>false,  // debug mode
        'jquery.min.js'=>false, // disable debug mode
    ); ?>
    <title><?php echo Yii::app()->name ; ?></title>


    <meta name="description" content="Professional Frontend framework that makes building websites easier than ever.">
    <!--    <link rel="shortcut icon" href="../vendor/assets/img/favicon.ico" type="image/x-icon" />-->
    <meta name="viewport" content="width=device-width">

    <style type="text/css">


    </style>

</head>
<body class="narrow">

<div class="">
    <?php echo $content; ?>
</div>

<div class="divider" style="background-color: red;height: 5px;border-bottom: 2px solid #2A333C;margin: 10px 0 ;"></div>
<div class="site-center">
    <div class="site-footer">
        <div class="cell">
            <div id="sociallogos">

                <a href="https://github.com/CascadeFramework"><span class="icon icon-32 icon-github"></span></a>
                <div class="col">
                    &#169; 2013, <a href="https://twitter.com/johnslegers">John Slegers</a>
                </div>
            </div>
            <a href="index.html" class="powered-by"></a>
        </div>
    </div>
</div>
</body>
</html>
