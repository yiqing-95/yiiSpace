<?php
  Yii::import('my.ui.easyui.*');
  EZui::registerCoreScripts();
?>

<div id="dd" class="easyui-draggable" data-options="handle:'#title'" style="width:100px;height:100px;">
    <div id="title" style="background:#ccc;">title</div>
</div>
    <div class="row-fluid">
        <div class="span12">
            <div class="row-fluid">
                <div class="row8">

                    <div id="pp" class="easyui-pagination "
                         data-options="total:2000,pageSize:10" style="background:#efefef;border:1px solid #ccc;"></div>
                </div>
            </div>
        </div>
    </div>

<div class="easyui-panel" title="Nested Panel" style="width:500px;height:200px;padding:10px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true" style="width:100px;padding:10px">
            Left Content
        </div>
        <div data-options="region:'center'" style="padding:10px">
            Right Content
        </div>
    </div>
</div>