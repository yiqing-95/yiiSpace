<?php
$cs = Yii::app()->clientScript;
$cs->registerCoreScript('jquery');
Yii::import('my.ui.easyui.*');
EZui::registerCoreScripts();
PublicAssets::registerScriptFile('js/currentActive.js');
PublicAssets::registerScriptFile('js/md5/md5.min.js'); ?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <Meta http-equiv="Page-Enter" Content="revealTrans(duration=2, transition=23)">
    <Meta http-equiv="Page-Exit" Content="revealTrans(duration=2, transition=23)">

    <meta name="language" content="<?php echo Yii::app()->language ?>"/>
    <script type="text/javascript">

    </script>
    <title><?php echo CHtml::encode(Yii::app()->name); ?></title>

    <script language="javascript">
        $(document).ready(function () {
            $(this).ajaxError(function (event, XMLHttpRequest, textStatus, errorThrown) {
                window.parent.window.$.messager.alert('<?php echo Yii::t('base', 'Operation failed'); ?>', XMLHttpRequest.status + ":" + XMLHttpRequest.responseText, 'error');
            });
        });
    </script>

</head>
<body class="easyui-layout">
<div data-options="region:'north',border:false" style="height:80px;padding:1px">

    <div class="row-fluid">
        <div class="span4 pull-right">
            <a href="<?php echo $this->createUrl('/menuBuilder'); ?>" target="_blank">menuBuilder</a>
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
        <div class="span9" style="">
            <?php
            $topMenuItems = array();
            foreach ($roots as $menuNode) {
                $topMenuItems[] = array('label' => $menuNode->label,
                    'url' => 'javascript:;',
                    // 'active'=>true,
                    'linkOptions' => array('id' => 'menu' . $menuNode->id, 'menu-id' => $menuNode->id, 'class' => 'header-nav-item')
                );
            }

            $this->widget('bootstrap.widgets.TbMenu', array(
                'type' => 'pills', // '', 'tabs', 'pills' (or 'list')
                'stacked' => false, // whether this is a stacked menu
                'items' => $topMenuItems,
                'htmlOptions' => array(
                    'id' => 'topNav', //  这个竟然不管用
                    'class' => 'pull-left header-nav',
                ),
                'id' => 'topNav'
            )); ?>

        </div>

    </div>

    <!--    渲染顶级菜单 end !-->

</div>
<div data-options="region:'west',split:true,title:'West'" style="width:150px;padding:10px;">
    <ul id="maintree" class="ztree"></ul>
</div>

<div data-options="region:'center',title:'Center'">
    <div id="app_main_tab" class="easyui-tabs" data-options="fit:true">
        <div title="<?php echo Yii::t('base', 'Home pagh'); ?>">
            <?php echo CHtml::normalizeUrl('admin/default/index');?>
        </div>
    </div>
</div>

<div id="app_main_tab_menu" class="easyui-menu">
    <div id="app_main_tab_update"><?php echo Yii::t('base', 'reload'); ?></div>
    <div class="menu-sep"></div>
    <div id="app_main_tab_tabclose"><?php echo Yii::t('base', 'Close'); ?></div>
    <div id="app_main_tab_tabcloseother"><?php echo Yii::t('base', 'Close Other'); ?></div>
    <div id="app_main_tab_tabcloseall"><?php echo Yii::t('base', 'Close All'); ?></div>
</div>
<!--    for resize the iframe -->
<a href="javascript:;" class="dummy" id="dummyLink"></a>
<!--    for resize the iframe /-->
<div style="display:none " id="hiddenFrames">
    for the hiddenFrames here
</div>
<?php
$hierarchyArr = AdminMenu::toHierarchy($descendants);
// print_r($hierarchyArr);

//$this->layout = false;
$this->widget('my.widgets.ztree.ZTree', array());
$iframeAutoHeightOptions = array(
    'animate' => false,
    'minHeight' => 400,
    'heightOffset' => 20,
    'triggerFunctions' => array('js:windowResizeFunction',
        'js:clickResizeFunction',
    ),
    'diagnostics'=>false,
    'callback'=>'js:onIFrameHeightChanged'
);
$this->widget('my.widgets.iframeAutoHeight.IFrameAutoHeight', array(
       // 'debug' => false,
        'options' => $iframeAutoHeightOptions,
    )
);

?>
<script type="text/javascript">
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
    function risizeIframe() {
        $('#dummyLink').click();
    }

    var iframeAutoHeightOptions = <?php echo CJavaScript::encode($iframeAutoHeightOptions); ?>;
    var iframeAutoHeightIds = []; // 缓存下 iframe的 id 防止重复实例化

    function onIFrameHeightChanged(callbackObject){
        var newHeight = callbackObject.newFrameHeight;
            //alert(""+newHeight);
        /**
         * 重新布局下
         * //  $('#ids').layout('panel', 'north').panel('resize',{height:300});
         * //  $('#ids').layout('resize');
         * //----------------------------------------------------------
         * $('#wrap').layout('resize');
         $('#subWrap').layout('panel', 'north').panel('resize',{width:$('#subWrap').width()});
         $('#subWrap').layout('panel', 'center').panel('resize',{width:$('#subWrap').width()});
         $('#subWrap').layout('resize');
         * ------------------------------------------------------------
         */
        $('body').layout('panel', 'west').panel('resize',{height:newHeight});
        $('body').layout('panel', 'center').panel('resize',{height:newHeight});
        $('body').layout('resize');
    }

</script>

<script type="text/javascript">
var maintree;
var mainTreeMyData = {};
var mainTempTreeData = {};
var mainTreeData = <?php echo CJSON::encode($hierarchyArr) ?>;
/**
 * 权限树
 */
var mainTreeSetting = {

    callback:{
        onClick:function (e, treeId, treeNode) {
            if (treeNode.isParent)
                return false;
            // alert(treeNode._url);
            if (treeNode._url) {
                mainAddTab(treeNode.name, treeNode._url);
            } else {
                alert("请为树节点提供_url 属性");
            }

        }
    }
};


var createFrame = function (url, iframeId) {
    var $existOne = $("#"+iframeId,"#hiddenFrames");
    var tmpWrapperId = "wrapper_"+md5(url); //makeid()
    if($existOne.size()>0){
        /*
         $($existOne).wrap("<div class='wrap' id='"+tmpWrapperId+"'></div>");
        var s = $("#"+tmpWrapperId).parent().html();
       // $("#"+tmpWrapperId).empty().remove();*/
        var s = "<div class='wrap' id='"+tmpWrapperId+"' style='width:100%;height:100%;'></div>";
        return s ;
    }else{
        var s = '<div id="'+tmpWrapperId+'" class="" style="width:100%;height:100%;">';
         s += '<iframe scrolling="auto" frameborder="0" style="width:100%;height:100%;"  src="' + url + '" class="auto-height" scrolling="no" frameborder="0" name="' + iframeId + '" id="' + iframeId + '"</iframe>';
         s += '</div>'
        return s;
    }

}
var mainAddTab = function (title, url) {
    if ($('#app_main_tab').tabs('exists', title)) {
        $('#app_main_tab').tabs('select', title);//选中并刷新

    } else {
        var iframeId = "contentFrame" + md5(url);


        var content = createFrame(url, iframeId);
        $('#app_main_tab').tabs('add', {
            title:title,
            content:content,
            closable:true
        });
        if($("#"+iframeId).size()>0){
            var tmpWrapperId = "wrapper_"+md5(url); //makeid()
            var tmpSrc = $("#"+iframeId).attr("tmp-src");
            $("#"+tmpWrapperId).append($("#"+iframeId).attr("src",tmpSrc));
        }
            jQuery('#' + iframeId).iframeAutoHeight(iframeAutoHeightOptions);

    }

}
function getWestHTml() {
    var NewLine = '\n';
    var temp = '';
    temp += '<div data-options="region:\'west\',split:true,iconCls:\'icon-tip\'" class="ztree" title="<?php echo Yii::t('base', 'Navigation menu') ?>" style="width:180px;">' + NewLine;
    temp += '	<ul id="maintree" class="ztree">' + NewLine;
    temp += '' + NewLine;
    temp += '	</ul>' + NewLine;
    temp += '</div>' + NewLine;
}
$(document).ready(function () {

    /* 为选项卡绑定右键 */
    $("#app_main_tab .tabs-inner").live('contextmenu', function (e) {
        $('#app_main_tab_menu').menu('show', {
            left:e.pageX,
            top:e.pageY
        });

        var subtitle = $(this).children(".tabs-closable").text();

        $('#app_main_tab_menu').data("currtab", subtitle);
        $('#app_main_tab').tabs('select', subtitle);
        return false;
    });

    //刷新
    $('#app_main_tab_update').click(function () {

        var currTab = $('#app_main_tab').tabs('getSelected');
        var url = $(currTab.panel('options').content).attr('src');
        if (url != undefined && currTab.panel('options').title != '<?php echo Yii::t('base ', 'Home pagh'); ?>') {
            var iframeId = "contentFrame" + md5(url)
            $('#app_main_tab').tabs('update', {
                tab:currTab,
                options:{
                    content:createFrame(url, iframeId)
                }
            });
            if($("#"+iframeId).size()>0){
                var tmpWrapperId = "wrapper_"+md5(url); //makeid()
                var tmpSrc = $("#"+iframeId).attr("tmp-src");
                $("#"+tmpWrapperId).append($("#"+iframeId).attr("src",tmpSrc));
            }
                jQuery('#' + iframeId).iframeAutoHeight(iframeAutoHeightOptions);

        }
    })
    //关闭当前
    $('#app_main_tab_tabclose').click(function () {
        var currtab_title = $('#app_main_tab_menu').data("currtab");

        $('#app_main_tab').tabs('close', currtab_title);
    })
    //全部关闭
    $('#app_main_tab_tabcloseall').click(function () {
        $('.tabs-inner span').each(function (i, n) {
            var t = $(n).text();
            if (t != '<?php echo Yii::t('base ', 'Home pagh'); ?>') {
                $('#app_main_tab').tabs('close', t);
            }
        });
    });
    //关闭除当前之外的TAB
    $('#app_main_tab_tabcloseother').click(function () {
        var prevall = $('.tabs-selected').prevAll();
        var nextall = $('.tabs-selected').nextAll();
        if (prevall.length > 0) {
            prevall.each(function (i, n) {
                var t = $('a:eq(0) span', $(n)).text();
                if (t != '<?php echo Yii::t('base ', 'Home pagh'); ?>') {
                    $('#app_main_tab').tabs('close', t);
                }
            });
        }
        if (nextall.length > 0) {
            nextall.each(function (i, n) {
                var t = $('a:eq(0) span', $(n)).text();
                if (t != '<?php echo Yii::t('base ', 'Home pagh'); ?>') {
                    $('#app_main_tab').tabs('close', t);
                }
            });
        }
        return false;
    });


    $("a", ".ztree").on('click', function () {
        return false;
    });


    $("a", ".header-nav").click(function () {
        // if($(this).attr('state') == 'current') return;
        var topMenuId = $(this).attr('menu-id');

        if (mainTempTreeData[topMenuId]) {
            maintree = $.fn.zTree.init($("#maintree"), mainTreeSetting, mainTempTreeData[topMenuId]);
        } else {
            $.each(mainTreeData, function (i, n) {
                if (n.id == topMenuId) {
                    mainTempTreeData[topMenuId] = n.children ? n.children : [];
                }
            });
            maintree = $.fn.zTree.init($("#maintree"), mainTreeSetting, mainTempTreeData[topMenuId]);
        }

    });

    $('#app_main_tab').tabs({
        border:false,
        onBeforeClose: function(title,index){
            var target = this;
            $.messager.confirm('Confirm','确定要关闭 '+title,function(r){
                if (r){
                    var opts = $(target).tabs('options');
                    var bc = opts.onBeforeClose;
                    opts.onBeforeClose = function(){};  // allowed to close now

                  // 将 iframe 转移到隐藏区
                   var tab =  $(target).tabs('getTab',index);
                    //alert($(tab).html());
                    var $iframe = $("iframe",$(tab));
                    // 将当前url存放在临时属性中 不然移动dom 导致iframe 重新加载内容
                    $iframe.attr("tmp-src",$iframe.attr("src")).attr("src","about:blank");
                    $("#hiddenFrames").append($iframe);

                    $(target).tabs('close',index);
                    opts.onBeforeClose = bc;  // restore the event function
                }
            });
            return false;	// prevent from closing
        }
    });

    //头部
    $('.heaber_top a[addtag="true"]').click(function () {

        mainAddTab($(this).attr('title'), $(this).attr('href'));
        return false;
    })

});


function in_array(needle, haystack, argStrict) {

    var key = '',
            strict = !!argStrict;

    if (strict) {
        for (key in haystack) {
            if (haystack[key] === needle) {
                return true;
            }
        }
    } else {
        for (key in haystack) {
            if (haystack[key] == needle) {
                return true;
            }
        }
    }

    return false;
}
function makeid()
{
    var text = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

    for( var i=0; i < 5; i++ )
        text += possible.charAt(Math.floor(Math.random() * possible.length));

    return text;
}
</script>

</body>
</html>