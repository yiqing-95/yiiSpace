

    <?php
    $this->widget('my.widgets.jcontextmenu.JContextMenu', array(
            'selector' => '#tree a',
            'callback' => 'js:function(key, options) {
             var m = "Clicked on " + key + " on element " + options.$trigger.attr("class");
             $("#msg").html(m);
        }',
            'items' => array(
                'create' => array('name' => '新增',
                    'callback' => 'js:function(key, options) {
                       var node =  $.ui.dynatree.getNode(options.$trigger);
                       var parentId = node.data.key;
                       debug("你要往"+parentId+ " 上添加节点了");
                       createMenu(parentId);

        }'
                ),
                'delete' => array('name' => '删除',
                    'callback' => 'js:function(key, options) {
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
                'edit' => array('name' => '编辑', /*'icon' => 'edit1',*/
                    'callback' => 'js:function(key, options) {
                       var node =  $.ui.dynatree.getNode(options.$trigger);
                       var nodeId = node.data.key;
                       debug("你要修改"+nodeId+ " 节点了");
                       editMenu(nodeId);

        }'
                ),
                'createTopMenu' => array('name' => '新增顶级菜单',/* 'icon' => 'create1',*/
                    'callback' => 'js:function(key, options) {
                       var parentId = -1;
                       debug("你要添加顶级跟节点了");
                       createMenu(parentId);

        }'
                ),
                'reload' => array('name' => '刷新', 'callback' => 'js:function(key, options) {
                                    var node =  $.ui.dynatree.getNode(options.$trigger);
                                        node.reloadChildren();
                                    }'
                ),
                'reloadAll' => array('name' => '刷新整个树', 'callback' => 'js:function(key, options) {
                                    var node =  $(this);
                                        debug(""+node);
                                    }'
                ),
                'sep1' => '---------',
                'quit' => array('name' => 'Quit', 'icon' => 'quit',),
            ),
        )
    );


    $this->widget('my.widgets.dynatree.JDynaTree', array(
        'container' => '#tree',
        'options' => array(
            'initAjax' => array('url' => $this->createUrl('initAjax')),
            'onLazyRead' => 'js:function(node){
                    node.appendAjax({
                 url: "' . $this->createUrl('loadChildren') . '",
                 data: {key: node.data.key,
                       mode: "funnyMode"
                         }
                 });
                    }',
            'dnd' => array('onDragStart' => 'js:function(node){return true}',
                'preventVoidMoves' => true,
                'onDragEnter' => 'js:function(node, sourceNode){
                    if(node.parent !== sourceNode.parent)
                         return false;
                         return ["before", "after"];
                    }',
                'onDrop' => 'js:function(node, sourceNode, hitMode, ui, draggable){
                       $("#msg").html( sourceNode.data.title+ " move "+hitMode+node.data.title);
                       if(move(hitMode,sourceNode.data.key,node.data.key)){
                          sourceNode.move(node, hitMode);
                       }else{
                         debug("move error  !");
                       }
                    }'
            ),
        ),
    ));

    ?>
    <div id="msg">
        this is for debug displaying .
    </div>


<div class="row">
        <div class="">
            <p>
                <a href="javascript:  $.ui.dynatree.getTree().reload() ;" accesskey="">刷新树</a>
            </p>

            <div id="tree" class="two column offset-by-one">

            </div>
            <div class="nine column">
                <?php
                $this->widget('my.widgets.iframeAutoHeight.IFrameAutoHeight', array(
                        'debug' => false
                    )
                );
                ?>
                <iframe src="http://localhost/my/yiiSpace/" width="100%" height="400px" id="helperFrame"></iframe>
            </div>
        </div>
</div>

<p style="clear: both;"></p>

    <h1><?php echo $this->id . '/' . $this->action->id; ?></h1>

    <p>
        <tt><?php echo __FILE__; ?></tt>.
    </p>

    <script type="text/javascript">
    function debug(msg) {
        $("#msg").html(msg);
    }
    //define node operations
    /**
     * @param moveMode  after before
     * @param sourceNodeId  the current active nodeId
     * @param refNodeId     reference node
     */
    function move(moveMode, sourceNodeId, refNodeId) {
        var url = "<?php echo  $this->createUrl('moveNode'); ?>";
        var params = {
            "moveMode":moveMode,
            "srcNode":sourceNodeId,
            "refNode":refNodeId
        };
        var response = $.ajax({
            url:url,
            data:params,
            type:"POST",
            async:false
            // ,dataType: "json"
        }).responseText;
        //debug(typeof response);
        response = $.parseJSON(response);
        return response.error;
    }

    /**
     * delete a node
     * @param nodeId
     */
    function deleteNode(nodeId) {
        if(!confirm("你真的想要删除么？","提醒")) return ;
        var url = "<?php echo  $this->createUrl('deleteNode'); ?>";
        var params = {
            "nodeId":nodeId
        };
        var response = $.ajax({
            url:url,
            data:params,
            type:"POST",
            async:false
            // ,dataType: "json"
        }).responseText;
        //debug(typeof response);
        response = $.parseJSON(response);
        return response.error;
    }

    function createMenu(parentId) {
        var url = "<?php echo  $this->createUrl('/adminMenu/create', array('layout' => 'false', 'parentId'=>'{parentId}')); ?>";
        url = url.replace(encodeURIComponent("{parentId}"), parentId);
        $("#helperFrame").attr('src',url);
    }

    function editMenu(nodeId) {
        var url = "<?php echo  $this->createUrl('/admin/update', array('id' => '{id}', 'layout' => 'false')); ?>";
        url = url.replace(encodeURIComponent("{id}"), nodeId);
        $("#helperFrame").attr('src',url);
    }
    $(function () {
        /*
        $("#helperFrame").load(function () {
            var $body = $(this).contents().find('body');
            $('form', $body).attr('target', 'helperFrame');
            $("#menuOpContainer").html($body.html());
            //刷新当前父亲节点：
            $.ui.dynatree.getActiveNode().reloadChildren();
        });
        */
    });
</script>


