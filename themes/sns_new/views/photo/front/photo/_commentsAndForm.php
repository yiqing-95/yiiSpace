
<?php
 // 以前Comment 类太多了 导致加载冲突了 所以先导入下comment模块 这样类加载搜索顺序就变了
 Yii::app()->getModule('comment');
$this->widget(
    'application.modules.comment.widgets.CommentsListWidget',
    array(
        // 在需要动态切换加之模型的评论列表时 注意id问题
        // 'id'=> get_class($model).'_'.$model->primaryKey,
        'id'=> get_class($model).'_list',
        // 'ajaxUrl'=>array('/comment/comment/commentList'),
        'model' => $model,
        'modelId' => $model->id,
        'canDelete'=>  $model->uid == user()->getId() ,  //true ,
        'canApprove'=>  $model->uid == user()->getId(),
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
        'modelOwnerId' => $model->uid,
        // 这里的数据要足以形成对某个实体的概要描述 链接 图片
        'modelProfileData' => CJSON::encode(array(
           //   'title'=>StringUtil::cnTruncate($model->summary,100),
                'id'=>$model->id,
        )),
    )
); ?>
