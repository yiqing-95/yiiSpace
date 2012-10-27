<?php echo Yii::app()->bootstrap->registerCss(); ?>



<h1><?php echo $this->id . '/' . $this->action->id; ?></h1>

<p>
 <tt><?php echo __FILE__; ?></tt>.
</p>


<div class="content">

        <?php
        $this->widget('common.widgets.jcontextmenu.JContextMenu', array(
                'selector' => '#tree a',
                'callback' => 'js:function(key, options) {
             var m = "Clicked on " + key + " on element " + options.$trigger.attr("class");
             $("#msg").html(m);
        }',
                'items' => array(
                    'create' => array('name' => '新增', 'icon' => 'create',
                        'callback'=>'js:function(key, options) {
                       var node =  $.ui.dynatree.getNode(options.$trigger);
                       var parentId = node.data.key;
                       debug("你要往"+parentId+ " 上添加节点了");
                       createMenu(parentId);

        }'
                    ),
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
                    'edit' => array('name' => '编辑', 'icon' => 'edit',
                        'callback'=>'js:function(key, options) {
                       var node =  $.ui.dynatree.getNode(options.$trigger);
                       var nodeId = node.data.key;
                       debug("你要修改"+nodeId+ " 节点了");
                       editMenu(nodeId);

        }'
                    ),
                    'createTopMenu' => array('name' => '新增顶级菜单', 'icon' => 'create',
                        'callback'=>'js:function(key, options) {
                       var parentId = -1;
                       debug("你要添加顶级跟节点了");
                       createMenu(parentId);

        }'
                    ),
                    'reload' => array('name' => '刷新', 'callback'=>'js:function(key, options) {
                                    var node =  $.ui.dynatree.getNode(options.$trigger);
                                        node.reloadChildren();
                                    }'
                    ),
                    'reloadAll' => array('name' => '刷新整个树', 'callback'=>'js:function(key, options) {
                                    var node =  $(this);
                                        debug(""+node);
                                    }'
                    ),
                    'sep1' => '---------',
                    'quit' => array('name' => 'Quit', 'icon' => 'quit',),
                ),
            )
        );


        $this->widget('common.widgets.dynatree.JDynaTree', array(
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
                'dnd'=>array('onDragStart'=>'js:function(node){return true}',
                        'preventVoidMoves'=>true,
                    'onDragEnter'=>'js:function(node, sourceNode){
                    if(node.parent !== sourceNode.parent)
                         return false;
                         return ["before", "after"];
                    }',
                    'onDrop'=>'js:function(node, sourceNode, hitMode, ui, draggable){
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




</div>
<div id="splitter_x">
    <div>
        <p>
            <a href="javascript:  $.ui.dynatree.getTree().reload() ;" accesskey="">刷新树</a>
        </p>
        <div id="tree">

        </div>
    </div>
    <div>
        <?php
        $this->widget('common.widgets.iframeAutoHeight.IFrameAutoHeight', array(
                'debug'=>false
            )
        );
        ?>
<!--        <div id="splitter2" style="height:100%;">-->
    <div id="menuOpContainer">

    </div>
<!--            <iframe src="http://localhost/my/livmx/site/index" width="100%"></iframe>-->
<!--        </div>-->
    </div>
</div>
<?php  $this->widget('wij.WijSplitter', array(
   'selector' => '#splitter',
    'theme'=>EWijmoWidget::THEME_STERLING,
    'options'=>'js:{ orientation: "vertical", fullSplit: true }',
    'debug'=>false,
    'css'=> '
        #splitter
        {
            width: 300px;
            height: 300px;
        }

    ',
    //'fullSplit'=> true
));
?>



<p style="clear: both;"></p>
    <script type="text/javascript">
        function debug(msg){
            $("#msg").html( msg);
        }
        //define node operations
        /**
         * @param moveMode  after before
         * @param sourceNodeId  the current active nodeId
         * @param refNodeId     reference node
         */
       function move(moveMode,sourceNodeId,refNodeId){
           var url = "<?php echo  $this->createUrl('moveNode'); ?>";
            var params = {
              "moveMode" :moveMode,
                "srcNode":sourceNodeId,
                "refNode":refNodeId
            };
            var response = $.ajax({
                url: url ,
                data: params,
                type: "POST",
                async: false
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
        function deleteNode(nodeId){
            var url = "<?php echo  $this->createUrl('deleteNode'); ?>";
            var params = {
                "nodeId":nodeId
            };
            var response = $.ajax({
                url: url ,
                data: params,
                type: "POST",
                async: false
                // ,dataType: "json"
            }).responseText;
            //debug(typeof response);
            response = $.parseJSON(response);
            return response.error;
        }

        function createMenu(parentId){
            var url = "<?php echo  $this->createUrl('//test2/SysMenu/create',array('layout'=>'false')); ?>";
            var params = {
                "parentId":parentId

            };
            $("#menuOpContainer").load(url,params,function(){
                $('form','#menuOpContainer').attr('target','helperFrame');

            });
        }

        function editMenu(nodeId){
            var url = "<?php echo  $this->createUrl('//test2/SysMenu/update',array('id'=>'{id}','layout'=>'false')); ?>";
            url = url.replace(encodeURIComponent("{id}"),nodeId);
            $("#menuOpContainer").load(url,function(){
                $('form','#menuOpContainer').attr('target','helperFrame');
            });
        }
       $(function(){
           $("#helperFrame").load(function(){
               var $body = $(this).contents().find('body');
               $('form',$body).attr('target','helperFrame');
               $("#menuOpContainer").html($body.html());
              //刷新当前父亲节点：
              $.ui.dynatree.getActiveNode().reloadChildren();
           });
       });
         </script>

<div class="container">
    <div class="header">
        <h2>
            Full Split</h2>
    </div>
    <div class="main demo" style = "height:300px">
        <!-- Begin demo markup -->
        <div id="splitter">
            <div>
                Panel 1
            </div>
            <div>
                Panel 2
            </div>
        </div>
        <!-- End demo markup -->
        <div class="demo-options">
            <!-- Begin options markup -->
            <!-- End options markup -->
        </div>
    </div>
    <div class="footer demo-description">
        <p>

            You can create a full-size splitter by setting the fullSplit option to true. Resize your Web browser and observe how the wijsplitter widget expands or contracts fluidly.
        </p>
    </div>
</div>

        <iframe name="helperFrame" id="helperFrame" style="" width="100%" height="100px"></iframe>