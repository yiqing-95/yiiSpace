<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <script type="text/javascript" src="<?php echo bu('public/sea-modules/seajs/seajs/2.1.1/sea.js');?>"></script>
    <script src="<?php echo bu('public/sea-modules/jquery/jquery/1.10.1/jquery.js') ?>" ></script>

    <script>

        seajs.config({
            alias: {
                "jquery": "jquery/jquery/1.10.1/jquery.js"
            }
        });

        seajs.use(['jquery'], function ($) {
           window.$ = $ ;
        });

        /*
         //  define( "jquery", [], function () {  return  $; } );
         define("jquery",[],function(require, exports, module){

         //原jquery.js代码...

         module.exports = $; // $.noConflict(true);
         });
         */
    </script>
    <?php $cs=Yii::app()->clientScript;
    $cs->scriptMap=array(
        'jquery.min.js'=>false,
        //'jquery-ui-1.8.custom.min.js'=>'/js/jquery-ui-1.8.custom.min.js',
        'jquery.js'=>false,
    );
    ?>

    <title><?php echo Yii::app()->name; ?></title>
    <meta name="description" content="<?php echo Yii::app()->params['description']; ?>">
    <meta name="author" content="<?php echo Yii::app()->params['author']; ?>">
    <!--Le fav and touch icons -->
    <link rel="apple-touch-icon" sizes="144x144" href="<?php echo bu('public/images/apple-touch-icon-144.png') ?>">
    <link rel="apple-touch-icon" sizes="114x114" href="<?php echo bu('public/images/apple-touch-icon-114.png') ?>">
    <link rel="apple-touch-icon" sizes="72x72" href="<?php echo bu('public/images/apple-touch-icon-72.png') ?>">
    <link rel="apple-touch-icon" href="<?php echo bu('public/images/apple-touch-icon-57.png') ?>">
    <link rel="shortcut icon" href="<?php echo bu('public/images/favicon.png') ?>">
    <?php PublicAssets::registerCssFile(array('css/uz_front.css')); ?>
    <?php PublicAssets::registerScriptFile(array('js/global.js', 'js/uz.js', 'vendors/holder/holder.js')); ?>

    <?php
    $pageId = time();
    Yii::app()->request->cookies['__pageId'] = new CHttpCookie('__pageId', $pageId);
    Yii::app()->user->setState('__pageId', $pageId);
    ?>
</head>
<body>
<header class="header">
    <div class="container site-nav-bd">
        <ul class="inline">
            <!--左边对齐的菜单项-->
            <li>
                <a href="javascript:;"
                   onclick="AddFavorite('<?php echo Yii::app()->name; ?>', '<?php echo Yii::app()->getBaseUrl(true); ?>')">
                    <i class="icon-star text-warning"></i>
                    收藏优泽
                </a>
            </li>
            <li class='site-nav-pipe'>|</li>
            <?php if (Yii::app()->user->isGuest) { ?>
                <li><span class="muted">您好，欢迎来到优泽！</span>
                    <?php echo CHtml::link('请登录', Yii::app()->getModule('user')->loginUrl,array('class'=>'text-orange')); ?>
                </li>
                <li><?php echo CHtml::link('免费注册', Yii::app()->getModule('user')->registrationUrl); ?></li>
                <li><?php echo CHtml::link('[激活]', Yii::app()->getModule('user')->activeUrl); ?></li>
            <?php } else { ?>
                <li><span class="muted">您好！<?php  $userModel = UserHelper::getLoginUserModel();
                        echo UserHelper::getWelcomeName($userModel);?>
                        <a href="<?php echo Yii::app()->createUrl('/user/logout'); ?>">【退出】</a></span></li>
            <?php } ?>

            <ul class="inline pull-right">
                <?php
                $topNavs = Menu::getTopLevelMenus('header_bar');
                //$i = 1;
                //$navCount = count($topNavs);
                foreach ($topNavs as $menu):
                    if(!empty($menu->visible)){
                        // echo '<li>', $menu->visible , '</li>' ;
                        if(Menu::evaluateVisibleExpression($menu->visible) == false){
                            continue ;
                        }
                    }
                    ?>
                    <li><a href="<?php echo $menu->createUrl(); ?>"><?php echo $menu->label; ?></a></li>
                    <?php
                    //if ($i < $navCount)
                    echo "<li class='site-nav-pipe'>|</li>";
                    //$i++;
                endforeach; ?>
            </ul>
        </ul>
    </div>
</header>
<div class="container">
    <?php
    //显示来自Cookie的消息提示
    $this->widget('bootstrap.widgets.TbAlert', array(
        'block' => true, // display a larger alert block?
        'fade' => true, // use transitions?
        'closeText' => '&times;', // close link text - if set to false, no close link is displayed
        'alerts' => array( // configurations per alert type
            'success' => array('block' => true, 'fade' => true, 'closeText' => '&times;'), // success, info, warning, error or danger
            'info' => array('block' => true, 'fade' => true, 'closeText' => '&times;'), // success, info, warning, error or danger
            'error' => array('block' => true, 'fade' => true, 'closeText' => '&times;'), // success, info, warning, error or danger
            'warning' => array('block' => true, 'fade' => true, 'closeText' => '&times;'), // success, info, warning, error or danger
        )));
    ?>
</div>
<!--    主布局不做宽度限制 交由下面的子布局决定 -->
<?php echo $content; ?>
<!-- 底部 -->
<footer class="footer">
    <div class="container font13">
        <!--<p class="powered"> 优泽简介| 新闻公告| 招纳贤士| 联系我们 | 意见反馈 </p>-->
        <p>
            客服热线：4000-966-058&nbsp;&nbsp;
            客服邮箱：service@uzcbn.com&nbsp;&nbsp;
            客服QQ：<img  style="CURSOR: pointer" onclick="javascript:window.open('http://b.qq.com/webc.htm?new=0&sid=1610306272&o=www.uzcbn.com&q=7', '_blank', 'height=502, width=644,toolbar=no,scrollbars=no,menubar=no,status=no');"  border="0" SRC=http://wpa.qq.com/pa?p=1:1610306272:1 alt="优泽QQ客服">&nbsp;&nbsp;
            优泽商业群：<a target="_blank" href="http://wp.qq.com/wpa/qunwpa?idkey=37eec43fb7357f8bd8ff8026ef0a1c1149e561550783ae71c09902e7a1b56830"><img border="0" src="http://pub.idqqimg.com/wpa/images/group.png" alt="江苏商业交流群2000人" title="江苏商业交流群2000人"></a>
        </p>
        <p class="copy">Copyright <?php echo ' &copy;' . date('Y') . '-2016'; ?> 优泽版权所有 <?php echo Yii::app()->name ?>
            ICP证备案：苏ICP备13055819号</p>
    </div>
</footer>
<?php
Yii::app()->clientScript->registerScript('dropmenu', "
$('.drop-toggle').hover(function(){
$('.drop-toggle').toggleClass('desc');
return false;})");
?>
<?php
//滚动到顶部
$this->widget('ext.scrolltop.ScrollTop', array(
    //Default values
    'fadeTransitionStart' => 10,
    'fadeTransitionEnd' => 200,
    'speed' => 'slow'));
/*
//优化的Alert消息提醒
$this->widget('application.my.widgets.jnotify.JNotify', array());
// 使用artdialog了

$this->widget('my.widgets.artDialog.ArtDialog',array(
    // 这个可以通过视图来复写也可以哦！
    'skin'=>'twitter',
));
*/
$this->widget('my.widgets.blockUI.JBlockUI');
?>


<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'confirmModal')); ?>
<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4>
        <!--        title-->
    </h4>
</div>
<div class="modal-body">
    <p id="model_msg">One fine body...</p>
</div>

<div class="modal-footer">
    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'type' => 'primary',
        'label' => '确定',
        'url' => '#',
        'htmlOptions' => array(
            'data-dismiss' => 'modal',
            'class' => 'model-confirm'
        ),
    )); ?>
    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'label' => '取消',
        'url' => '#',
        'htmlOptions' => array('data-dismiss' => 'modal'),
    )); ?>
</div>
<?php $this->endWidget(); ?>
<script type="text/javascript">
    $('.header').smartFloat();
</script>
</body>
</html>