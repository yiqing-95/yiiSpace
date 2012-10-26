<style type="text/css">
        /*.content div {*/
        /*float: left;*/
        /*}*/
</style>
<?php
$this->breadcrumbs = array(
    'File Tree',
);?>
<h1><?php echo $this->id . '/' . $this->action->id; ?></h1>

<p>
    You may change the content of this page by modifying
    the file <tt><?php echo __FILE__; ?></tt>.
</p>


<div class="content">
    <div class="row">
        <?php
        $this->widget('my.widgets.jcontextmenu.JContextMenu', array(
                'selector' => '#tree a',
                'callback' => 'js:function(key, options) {
             var m = "Clicked on " + key + " on element " + options.$trigger.attr("class");
             $("#msg").html(m);
        }',
                'items' => array(
                    'getAlias' => array('name' => '获取路径别名', 'callback' => 'js:function(key, options) {
                                    var node =  $.ui.dynatree.getNode(options.$trigger);
                                       getAliasOfPath(node.data.key);
                                    }'
                    ),
                    'getRoutes' => array('name' => '获取访问路由', 'callback' => 'js:function(key, options) {
                                    var node =  $.ui.dynatree.getNode(options.$trigger);
                                       getRoutes(node.data.key);
                                    }'
                    ),
                    'reload' => array('name' => '刷新', 'callback' => 'js:function(key, options) {
                                    var node =  $.ui.dynatree.getNode(options.$trigger);
                                        node.reloadChildren();
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
                'initAjax' => array('url' => $this->createUrl('loadChildren')),
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

        <div id="tree" class="tree columns offset-by-one ">

        </div>
        <div id="msg" class="six columns end">
            this is for debug displaying .
        </div>
    </div>
</div>
<p style="clear: both;"></p>
<script type="text/javascript">
    function debug(msg) {
        $("#msg").html(msg);
    }


    function getAliasOfPath(path) {
        var url = "<?php echo  $this->createUrl('getAliasOfPath'); ?>";
        var params = {
            "path":path
        };
        var response = $.ajax({
            url:url,
            data:params,
            type:"POST",
            async:false
            // ,dataType: "json"
        }).responseText;
        debug(response);
    }

    function getRoutes(path) {
        var url = "<?php echo  $this->createUrl('getRoutes'); ?>";
        var params = {
            "path":path
        };
        var response = $.ajax({
            url:url,
            data:params,
            type:"POST",
            async:false
            // ,dataType: "json"
        }).responseText;
        debug(response);
    }
</script>