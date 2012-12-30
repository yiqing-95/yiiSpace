<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php echo CHtml::encode(Yii::app()->name); ?></title>
    <?php //WebUtil::bootSwatch() ;
    WebUtil::registerRandomBootstrapTheme();
    //
    $themeName = (isset(Yii::app()->request->cookies['themeName'])) ? Yii::app()->request->cookies['themeName']->value : '';

    if (empty($themeName)) {
        $themeUrl = '';
    } else {
        $themeUrl = Yii::app()->request->baseUrl . "/public/bootswatch2-0-4/{$themeName}/bootstrap.min.css";
    }
    ?>

    <link id="theme_style" rel="stylesheet"
          href="<?php  echo $themeUrl?>" type="text/css"
          media="screen"/>

    <script type="text/javascript">
        function changeTheme(ddlEle) {
            //  alert($(ddlEle).val());
            var themeStyle = "";
            if ($(ddlEle).val().length !== 0) {
                themeStyle = "<?php echo Yii::app()->request->baseUrl; ?>/public/bootswatch2-0-4/{themeName}/bootstrap.min.css";
            }
            $("#theme_style").attr("href", themeStyle.replace("{themeName}", $(ddlEle).val()));
            /**
             * write it  to cookie
             * http://www.yiiframework.com/wiki/152/cookie-management-in-yii
             */
            $.cookie('themeName', $(ddlEle).val());
        }
        /**
         * http://www.eirikhoem.net/blog/2011/08/29/yii-framework-preventing-duplicate-jscss-includes-for-ajax-requests/
         */
        $.ajaxSetup({
            global:true,
            dataFilter:function (data, type) {
                var getScriptUrl = function (entry) {
                    if (entry.type == "text/css") {
                        return entry.href;
                    }
                    return entry.src;
                };
                // only ‘text’ and ‘html’ dataType should be filtered
                if (type && type != "html" && type != "text") {
                    return data;
                }
                var selector = 'script[src],link[rel="stylesheet"] ';
                // get loaded scripts from DOM the first time we execute.
                if (!$._loadedScripts) {
                    $._loadedScripts = {};
                    $._dataHolder = $(document.createElement('div'));

                    var loadedScripts = $(document).find(selector);

                    //fetching scripts from the DOM
                    for (var i = 0, len = loadedScripts.length; i < len; i++) {
                        $._loadedScripts[getScriptUrl(loadedScripts[i])] = 1;
                    }
                }
                //$._dataHolder.html(data) does not work
                $._dataHolder[0].innerHTML = data;
                // iterate over new scripts and remove if source is already in DOM:
                var incomingScripts = $($._dataHolder).find(selector);
                for (var i = 0, len = incomingScripts.length; i < len; i++) {
                    if ($._loadedScripts[getScriptUrl(incomingScripts[i])]) {
                        $(incomingScripts[i]).remove();
                    }
                    else {
                        $._loadedScripts[getScriptUrl(incomingScripts[i])] = 1;
                    }
                }
                return $._dataHolder[0].innerHTML;
            }
        });
    </script>
</head>

<body>

<?php

$topUserMenus = array();
if (Yii::app()->user->isGuest) {
    $topUserMenus = array(
        array('label' => 'Home', 'url' => array('/site/index')),
        array('url' => Yii::app()->getModule('user')->loginUrl, 'label' => Yii::app()->getModule('user')->t("Login"),),
        array('url' => Yii::app()->getModule('user')->registrationUrl, 'label' => Yii::app()->getModule('user')->t("Register"),),
        array('label' => 'About', 'url' => array('/site/page', 'view' => 'about')),
    );
} else {

    $topUserMenus = array(
        array('label' => Yii::app()->user->name, 'url' => Yii::app()->getModule('user')->returnUrl,),
        //array('url' => Yii::app()->getModule('user')->profileUrl, 'label' => Yii::app()->getModule('user')->t("Profile"), 'visible' => !Yii::app()->user->isGuest),
        '---',
        array('label' => '消息', 'url' => '#', 'items' => array(
            array('label' => '私信', 'url' => '#'),
            array('label' => 'Another action', 'url' => '#'),
            array('label' => 'Something else here', 'url' => '#'),
            '---',
            array('label' => 'Separated link', 'url' => '#'),
        )),
        '---',
        array('label' => '设置', 'url' => '#', 'items' => array(
            array('label' => '图像', 'url' => array('/user/settings/photo')),
            array('label' => 'Another action', 'url' => '#'),
            array('label' => 'Something else here', 'url' => '#'),
            '---',
            array('label' => 'Separated link', 'url' => '#'),
        )),
        '---',
        array('url' => Yii::app()->getModule('user')->logoutUrl, 'label' => Yii::app()->getModule('user')->t("Logout") . ' (' . Yii::app()->user->name . ')', 'visible' => !Yii::app()->user->isGuest),
    );
}

//换肤实现
$themesDir = Yii::getPathOfAlias('webroot.public.bootswatch2-0-4');
$di = new DirectoryIterator($themesDir);
$themes = array();
foreach ($di as $d) {
    if (!$d->isDot()) {
        if ($d->getFilename() !== 'img') {
            $themes[] = $d->getFilename();
        }

    }
}





$this->widget('bootstrap.widgets.TbNavbar', array(
    //'type'=>'inverse',
    'fluid' => true,
    'brand' => CHtml::encode(Yii::app()->name),
    'collapse' => true,
    'items' => array(
        array(
            'class' => 'bootstrap.widgets.TbMenu',
            'items' => array(
                array('url' => array('/user/user/space', 'u' => Yii::app()->user->isGuest ? 0 : Yii::app()->user->getId()), 'label' => '个人首页',
                    'visible' => !Yii::app()->user->isGuest),
                array('label' => 'user', 'url' => array('/user/user/index'),
                    'active' => Yii::app()->controller->id === 'site' && Yii::app()->controller->action->id === 'index'),
                array('label' => 'blog', 'url' => Yii::app()->homeUrl,
                    'active' => Yii::app()->controller->id === 'blog' && Yii::app()->controller->action->id === 'index'),

            ),
            'htmlOptions' => array('class' => 'pull-left'),
        ),
        '<form class="navbar-search pull-left" action="">' .
            CHtml::dropDownList('chang_theme', '', array('' => '请选择') + array_combine($themes, $themes), array('onchange' => 'changeTheme(this)', 'class' => 'input-mini'))
            . '</form>',
        array(
            'class' => 'bootstrap.widgets.TbMenu',
            'items' => $topUserMenus,
            'htmlOptions' => array('class' => 'pull-right'),
        ),
    ),
)); ?>
<div style="margin-top: 90px;">

</div>
<!--    主布局不做宽度限制 交由下面的子布局决定-->
<?php echo $content; ?>

<hr/>
<div class="row" style="text-align: center;">
    <footer>
        <p class="powered">
            Powered
            by <?php echo CHtml::link('Yii PHP framework', 'http://www.yiiframework.com', array('target' => '_blank')); ?>
            /
            <?php echo CHtml::link('jQuery', 'http://www.jquery.com', array('target' => '_blank')); ?> /
            <?php echo CHtml::link('YiiBooster', 'http://yii-booster.clevertech.biz/', array('target' => '_blank')); ?>
            /
        </p>

        <p class="copy">
            &copy;YiiSpace <?php echo date('Y'); ?>
        </p>

    </footer>
</div>
<?php
$this->widget('ext.scrolltop.ScrollTop',
    array(
        //Default values
        'fadeTransitionStart' => 10,
        'fadeTransitionEnd' => 200,
        'speed' => 'slow'
    ));
$this->widget('application.my.widgets.jnotify.JNotify',
    array(
    ));


?>
<script type="text/javascript">
    $(function(){
       // jSuccess('welcome to yiiSpace!');
    });
</script>

</body>

</html>