<?php
$this->breadcrumbs = array(
    'Groups' => array('index'),
    $model->name,
);
/*
$this->menu=array(
	array('label'=>'List Group','url'=>array('index')),
	array('label'=>'Create Group','url'=>array('create')),
	array('label'=>'Update Group','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete Group','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
    array('label'=>'Manage Group(advanced mode) ','url'=>array('adminAdv')),
);*/
?>
<a class="join-group" id="action_join_group" data-group="<?php echo $model->primaryKey?>">加入该组</a>

<div class="container ">
    <div class="col">
        <div class="menu cell  col">
            <ul class="links nav">
                <li class="">
                    <a href="#" class="">Latest</a>
                </li>
                <li class="active"><a href="#"> Hottest </a></li>
                <li><a href="#">most comment </a></li>
            </ul>
        </div>

    </div>

</div>

<div class="container site-body">

    <div>
        <h1>View Group #<?php echo $model->id; ?></h1>

        <?php $this->widget('zii.widgets.CDetailView', array(
            'data' => $model,
            'attributes' => array(
                'id',
                'name',
                'description',
                'creator',
                'created',
                'type',
                'active',
            ),
        )); ?>

    </div>

    <div class="cell">


        <div class="col width-6of9">

            <div class="col">
                <div class="menu cell  col width-8of9">
                    <ul class="links nav float-left">
                        <li class="">
                            <a href="#" class="">Latest</a>
                        </li>
                        <li class="active"><a href="#"> Hottest </a></li>
                        <li><a href="#">most comment </a></li>

                        <li><a href="#">全部</a></li>

                    </ul>
                    <ul class="links nav float-right">

                        <li class=" float-right">
                            <a href="<?php echo $this->createUrl('groupTopic/create',array('groupId'=>$model->primaryKey)) ?>" class="" target="_blank">创建topic</a>
                        </li>
                    </ul>
                </div>
            </div>

            <?php
            $this->widget('zii.widgets.grid.CGridView', array(
                'id' => 'group-topic-grid',
                'afterAjaxUpdate' => 'js:function(id, data){

                }',
                'itemsCssClass' => 'table',
                'dataProvider' => $dataProvider,
                //'filter'=>false,
                'selectableRows' => 2,
                'summaryCssClass' => '', // summary
                'template' => "{pager}<div class='row-fluid'><div>{summary}</div></div>\n{items}\n{summary}\n{pager}",
                'columns' => array(
                    array(
                        'class' => 'CCheckBoxColumn',
                        'id' => 'ids',
                        'selectableRows' => 2,
                    ),
                    'id',
                    'name',
                   array(
                       'class'=>'CLinkColumn',
                       'labelExpression'=>'$data->name',
                       'urlExpression'=>'controller()->createUrl("groupTopic/view",array("id"=>$data->primaryKey))',
                       'linkHtmlOptions'=>array(
                           'target'=>'_blank',
                       )
                   ),
                    'creator',
                    'created',
                    'active',
                    array(
                        'class' => 'CButtonColumn',
                        'htmlOptions' => array('class' => 'span2'),
                    ),
                ),
            ));
            ?>

        </div>

        <div class="col width-fill">
            <div class="panel" id="group_members">
                <span class="icon spin icon-64 icon-spinner"></span>
                加载中....

                <script type="text/javascript">
                    $(function () {
                        setTimeout(function () {
                            var url = '<?php echo $this->createUrl('latestJoined',array('id'=>$model->primaryKey)); ?>';
                            var params = {
                            };
                            $("#group_members").load(url);
                        }, 50);
                    });
                </script>
            </div>
            <div>
                <a href="<?php echo $this->createUrl('allMembers',array('id'=>$model->primaryKey)) ?>" target="_blank">全部组员</a>
            </div>
        </div>
    </div>

</div>

<?php
$this->widget('my.widgets.artDialog.ArtFormDialog', array(
        'link' => '#ajax_login',
        'options' => array(
            'onSuccess' => 'js:onAfterAjaxLogin',
        ),
        'dialogOptions' => array(
            'title' => '登录',
            'width' => 500,
            'height' => 370,

        )
    )
);
?>
<a style="display:none" href="<?php echo $this->createUrl('/user/ajaxLogin'); ?>" id="ajax_login">登录</a>
<script type="text/javascript">
    // 登录成功后的回调方法 变量可被复写！
    var onAfterAjaxLogin = function(data, ele){
       // 手动触发申请
        $('a.join-group').get(0).click();
    };

    $(function(){
       $('a.join-group').on('click',function(){
            // alert($(this).attr('data-group'));
           var groupId = $(this).attr('data-group');
           joinGroup(groupId);
       }) ;
    });
    function joinGroup(groupId){
        var actionUrl = '<?php echo $this->createUrl('joinGroup');?>';
        var params = {
          "groupId":groupId
        };
        $.ajax({
            type: "POST",
            url: actionUrl,
            data: params,
            success: function(data){
                // alert( "Data Saved: " + msg );
                if(data == '<?php echo user()->loginRequiredAjaxResponse ?>'){
                    document.getElementById('ajax_login').click();
                }else{
                    data = $.parseJSON(data);
                    alert(data.msg);
                }
            }
        });
    }
</script>

