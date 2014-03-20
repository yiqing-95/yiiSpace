<script src="<?php echo bu('public/sea-modules/seajs/seajs/2.1.1/sea.js') ?>" id="seajsnode"></script>
<?php
  Yii::import('foy.widgets.alice.*');

 AliceUI::registerCoreCss() ;

?>
<style>
    .wrapper {
        width: 990px;
        margin: 0 auto;
    }
    h1 {
        font-size: 26px;
    }
    .ui-grid-row {
        margin-bottom: 15px;
    }
</style>

<div class="wrapper">
    <div class="ui-grid-row">
        <div class="ui-grid-25">
            <h1>这是一个简陋的页面</h1>
        </div>
    </div>
    <div class="ui-grid-row">
        <div class="ui-grid-25">
            <div class="ui-nav">
                <ul class="ui-nav-main">
                    <li class="ui-nav-item">
                        <a href="#">一级导航 1</a>
                        <ul class="ui-nav-submain">
                            <li class="ui-nav-subitem ui-nav-subitem-current"><a href="#">二级导航 1-1</a></li>
                            <li class="ui-nav-subitem"><a href="#">二级导航 1-2</a></li>
                            <li class="ui-nav-subitem"><a href="#">二级导航 1-3</a></li>
                        </ul>
                    </li>
                    <li class="ui-nav-item ui-nav-item-current">
                        <a href="#">一级导航 2</a>
                        <ul class="ui-nav-submain">
                            <li class="ui-nav-subitem"><a href="#">二级导航 2-1</a></li>
                            <li class="ui-nav-subitem ui-nav-subitem-current"><a href="#">二级导航 2-2</a></li>
                            <li class="ui-nav-subitem"><a href="#">二级导航 2-3</a></li>
                        </ul>
                    </li>
                    <li class="ui-nav-item">
                        <a href="#">一级导航 3</a>
                        <ul class="ui-nav-submain">
                            <li class="ui-nav-subitem"><a href="#">二级导航 3-1</a></li>
                            <li class="ui-nav-subitem"><a href="#">二级导航 3-2</a></li>
                            <li class="ui-nav-subitem ui-nav-subitem-current"><a href="#">二级导航 3-3</a></li>
                        </ul>
                    </li>
                    <li class="ui-nav-item"><a href="#">一级导航 4</a></li>
                </ul>
                <div class="ui-nav-subcontainer"></div>
            </div>
        </div>
    </div>
    <div class="ui-grid-row">
        <div class="ui-grid-6">


            <div class="ui-box">
                <div class="ui-box-head">
                    <div class="ui-box-head-border">
                        <h3 class="ui-box-head-title">区块标题</h3>
                        <span class="ui-box-head-text">其他文字</span>
                        <a href="#" class="ui-box-head-more">更多</a>
                    </div>
                </div>
                <div class="ui-box-container">
                    <div class="ui-box-content">
                        <ul class="ui-list">
                            <li class="ui-list-item"><a href="#">如何申请认证？</a></li>
                            <li class="ui-list-item"><a href="#">如何提现？</a></li>
                            <li class="ui-list-item"><a href="#">支付宝数字证书有什么作用？</a></li>
                            <li class="ui-list-item"><a href="#">如何申请认证？</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="ui-grid-19">
            <div class="ui-tipbox ui-tipbox-success">
                <div class="ui-tipbox-icon">
                    <i class="iconfont" title="成功">ï</i>
                </div>
                <div class="ui-tipbox-content">
                    <h3 class="ui-tipbox-title">成功标题</h3>
                    <p class="ui-tipbox-explain">完成的说明完成的说明。</p>
                    <p class="ui-tipbox-explain"><a href="#">查询缴费记录</a> | <a href="#">我的支付宝</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

============================================================

<style>
    .panel {
        display: none;
    }
</style>

<div class="ui-tab">
    <ul class="ui-tab-items">
        <li class="ui-tab-item ui-tab-item-current">
            <a href="javascript:;">全部交易</a>
        </li>
        <li class="ui-tab-item">
            <a href="javascript:;">进行中的交易</a>
        </li>
        <li class="ui-tab-item">
            <a href="javascript:;">等待发货的交易</a>
        </li>
        <li class="ui-tab-item">
            <a href="javascript:;">未确认收获的交易</a>
        </li>
    </ul>
</div>

<ul class="panels">
    <li class="panel">面板一的内容</li>
    <li class="panel">面板二的内容</li>
    <li class="panel">面板三的内容</li>
    <li class="panel">面板四的内容</li>
</ul>
<script type="text/javascript">

//这个是好用的 
seajs.config({
    alias: {
         //  "jquery": "jquery/jquery/1.10.1/jquery.js",
         "$": "jquery/jquery/1.10.1/jquery.js"
    }
});

/*
seajs.config({
    // base: "http://localhost",

    plugins: ['shim'],

    alias: {
     
	   'jquery': {
            src: 'jquery/jquery/1.10.1/jquery.js',
            exports: 'jQuery'
        },
		
      '$': {
            src: 'jquery/jquery/1.10.1/jquery.js',
            exports: '$'
        }
    }
}
*/
/*
// 配置 jquery 并放入预加载项中
seajs.config({
  alias: {
    'jquery': 'jquery/jquery/1.10.1/jquery.js',
    '$': 'jquery/jquery/1.10.1/jquery.js'
  },
  preload: ["jquery"]
})

// 将 jQuery 暴露到全 没必要了 seajs修改过的jquery版本内部已经做了 只要preload了就自动做了
seajs.use('jquery', function(exports) {
  window.jQuery = window.$ = exports
});
*/

    seajs.use('arale/switchable/1.0.1/tabs', function(Tabs) {
        new Tabs({
            element: '.ui-tab',
            triggers: '.ui-tab-item',
            panels: '.panel',
            triggerType: 'click',
            activeTriggerClass: 'ui-tab-item-current'
        }).render();
    });

    seajs.use('arale/calendar/1.0.0/calendar', function(Calendar) {
        new Calendar({trigger: '#date-nothing'});
    });
</script>


<input id="date-nothing" type="text" placeholder="选择日期" />
<link rel="stylesheet" href="http://assets.spmjs.org/arale/calendar/1.0.0/calendar.css" />
4