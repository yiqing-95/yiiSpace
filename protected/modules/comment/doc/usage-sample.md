~~~
<?php
  $userId = isset($_GET['modelId'])? $_GET['modelId'] :  user()->getId() ;

  $model = User::model()->findByPk($userId);
?>


<a onclick="ajaxLoadComments()" id="ajaxLoadComments" >测试ajax加载评论列表 </a>
<a onclick="ajaxLoadComments2()" id="ajaxLoadComments" >ajax加载评论列表 url同源 只不过模型id不一样了 </a>

<script type="text/javascript">
function ajaxLoadComments(){
    $.fn.yiiListView.update('User_list',{
       url:"<?php echo $this->createUrl('/comment/comment/commentList',array('model'=>'User','modelId'=>2)) ?>"
    });
}
function ajaxLoadComments2(){
    $.fn.yiiListView.update('User_list',{
       url:"<?php echo $this->createUrl($this->route,array('modelId'=>2)) ?>"
    });
}
</script>

<?php

  $this->widget(
    'application.modules.comment.widgets.CommentsListWidget',
    array(
        // 在需要动态切换加载模型的评论列表时 注意id问题
        // 'id'=> get_class($model).'_'.$model->primaryKey,
        'id'=> get_class($model).'_list',
       // 'ajaxUrl'=>array('/comment/comment/commentList'),
        'model' => $model,
        'modelId' => $model->id,
        'canDelete'=>true ,
        'canApprove'=>true ,
    )
);


?>

<?php

$this->widget(
    'application.modules.comment.widgets.CommentFormWidget',
    array(
        'redirectTo' =>  request()->getUrl(), // $this->createUrl('/gallery/gallery/foto/', array('id' => $model->id)),
        'model' => $model,
        'modelId' => $model->id,

    )
); ?>


~~~