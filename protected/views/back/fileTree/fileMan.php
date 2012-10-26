<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>File Manager</title>
    <?php   Yii::app()->getClientScript()->registerCoreScript('jquery'); ?>
</head>
<body>
<?php
$this->widget('my.widgets.jcontextmenu.JContextMenu', array(
        'selector' => '#fileView .File',
        'callback' => 'js:function(key, options) {
             var m = "Clicked on " + key + " on element " + options.$trigger.attr("class");
             $("#msg").html(m);
        }',
        'items' => array(
            'getAlias' => array('name' => '获取路径别名', 'callback'=>'js:function(key, options) {
                                    var node =  $.ui.dynatree.getNode(options.$trigger);
                                       getAliasOfPath(node.data.key);
                                    }'
            ),
            'getRoutes' => array('name' => '获取访问路由', 'callback'=>'js:function(key, options) {
                                    var node =  $.ui.dynatree.getNode(options.$trigger);
                                       getRoutes(node.data.key);
                                    }'
            ),
            'create' => array('name' => '新增', 'icon' => 'create'),
            'delete' => array('name' => '删除', 'icon' => 'delete',
                'callback'=>'js:function(key, options) {
                       var node =  $.ui.dynatree.getNode(options.$trigger);
                       if(deleteNode(node.data.key)){
                         debug("success .. deleted");
                         node.remove();
                       }else{
                         debug("failed .. deleted");
                       }
             var m = "Clicked on " + key + " on element " + options.$trigger.html()+"|"+ options.$trigger+"|"+node.data.key;
             debug(m);
        }'
            ),
            'edit' => array('name' => '编辑', 'icon' => 'edit',),
            'reload' => array('name' => '刷新', 'callback'=>'js:function(key, options) {
                                    var node =  $.ui.dynatree.getNode(options.$trigger);
                                        node.reloadChildren();
                                    }'
            ),
            'sep1' => '---------',
            'quit' => array('name' => 'Quit', 'icon' => 'quit',),
        ),
    )
);

?>

<div id="mainContent">

    <div>
        <div id="file_op">
            <div id="msg"></div>
            <script type="text/javascript">
                $(function () {
                    /*  这里URL 编码有个bug  在pathInfo时 问号会影响的 所以用post方法 */
                    $(":input", 'form').on('change', function () {
                        var $form = $(this).closest('form');
                        var url = $($form).attr('action');
                        var data = $($form).serialize();
                        $form.submit();
                       /*
                        $.post(url, data, function (response) {
                            var updateId = '#mainContent';
                            var $data = $('<div>' + response + '</div>');
                            //$(updateId).replaceWith($(updateId, $data));
                            $(this).closest("body").replaceWith($("body", $data));
                        });
                        */
                    });
                });

            </script>
            <?php $form = $this->beginWidget('GxActiveForm', array(
            // 'action' => $this->createUrl(''),// Yii::app()->createUrl($this->route),
            'method' => 'post',
        ));
            ?>
            <table border="1">
                <tbody>
                <tr>
                    <td>
                        <a href="<?php echo $this->createUrl('', CMap::mergeArray($_GET + $_POST, array('act' => 'toParent')))  ?>">移至上一级</a>
                    </td>
                    <td>
                        <?php echo $form->label($model, 'viewStyle'); ?>:
                        <?php echo $form->dropDownList($model, 'viewStyle', array(
                        'icon' => '缩略图',
                        'detailList' => '详细信息'
                    ), array(
                        'class' => 'filter'
                    )); ?>
                    </td>
                    <td>
                        <?php echo $form->label($model, 'orderStyle'); ?>:
                        <?php echo $form->dropDownList($model, 'orderStyle', array(
                        'name' => '名称',
                        'size' => '大小',
                        'type' => '类型',
                        'ctime' => '修改时间',
                    ), array(
                        'class' => 'filter'
                    )); ?>
                    </td>
                    <td><a href="javascript:{}" onclick="onlyDisplayImageFile()">只显示图片</a>
                        <a href="javascript:{}" onclick="displayAllFile()">全部显示</a>
                    </td>
                    <td>
                        总文件数：<span id="totalFileCount"><?php echo $dataProvider->getTotalItemCount();  ?></span>
                        图片文件数 <span id="imageFileCount"></span>
                    </td>
                </tr>
                </tbody>
            </table>
            <?php $this->endWidget(); ?>

        </div>

        <div id="fileView">
            <?php

            // ArrayUtil::printArray($dataProvider->getData());
            /*   */
            if (isset($model->viewStyle) && $model->viewStyle == 'detailList') {
                $this->widget('zii.widgets.grid.CGridView', array(
                   'id' => 'file-list-grid',
                    'template' => "{summary}\n{pager}\n{items}\n{pager}",
                    'dataProvider' => $dataProvider,
                    'columns' => array(
                        'name',
                        'size',
                        'ctime',

                    ),
                ));

            } else {
                $this->widget('common.widgets.EMediaGridView', array(
                    'id' => 'file-list-grid',
                    'template' => "{summary}\n{pager}\n{items}\n{pager}",
                    'keyField' => '',
                    'dataProvider' => $dataProvider,
                    'itemView' => '_file',
                ));
            }
            ?>

        </div>
    </div>
</div>
</body>
</html>
