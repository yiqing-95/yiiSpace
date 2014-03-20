<?php
$this->breadcrumbs=array(
	$model->title,
);
$this->pageTitle=$model->title;
?>
<?php Layout::beginBlock('top') ?>
<?php YsPageBox::beginPanel() ;?>
 <div class="cell">
     <h2><?php echo CHtml::link(CHtml::encode($model->title), $model->url); ?></h2>

 </div>

<?php YsPageBox::endPanel(); ?>
<?php Layout::endBlock() ; ?>


<div class="col">
        col
        <div class="col">
            <div class="col ">

                    <?php YsPageBox::beginPanel() ;?>
                    <div class="cell">

                        <?php /*$this->renderPartial('_view', array(
                            'data'=>$model,
                        ));*/
                        $data = $model ;
 ?>
                        <div class="post">
                            <div class="title">
                                <h2><?php echo CHtml::link(CHtml::encode($data->title), $data->url); ?></h2>
                            </div>
                            <div class="author">
                                posted by <?php echo $data->author->username . ' on ' . date('F j, Y',$data->created); ?>
                                <!-- Baidu Button BEGIN -->
                                <div id="bdshare" class="bdshare_t bds_tools get-codes-bdshare">
                                    <a class="bds_qzone"></a>
                                    <a class="bds_tsina"></a>
                                    <a class="bds_tqq"></a>
                                    <a class="bds_renren"></a>
                                    <span class="bds_more">更多</span>
                                    <a class="shareCount"></a>
                                </div>
                                <script type="text/javascript" id="bdshare_js" data="type=tools&amp;uid=138127" ></script>
                                <script type="text/javascript" id="bdshell_js"></script>
                                <script type="text/javascript">
                                    document.getElementById("bdshell_js").src = "http://bdimg.share.baidu.com/static/js/shell_v2.js?cdnversion=" + new Date().getHours();
                                </script>
                                <!-- Baidu Button END -->
                                <div class="clear"></div>
                            </div>
                            <div class="content">
                                <?php
                                $this->beginWidget('CMarkdown', array('purifyOutput'=>true));
                                echo $data->content;
                                $this->endWidget();
                                ?>
                            </div>
                            <div class="nav">
                                <b>Tags:</b>
                                <?php echo implode(', ', $data->tagLinks); ?>
                                <br/>
                                <?php echo CHtml::link('Permalink', $data->url); ?> |
                                <?php echo CHtml::link("Comments ({$data->commentCount})",$data->url.'#comments'); ?> |
                                Last updated on <?php echo date('F j, Y',$data->updated); ?>
                            </div>
                        </div>
                        <div class="object_op">
                            <?php if(!user()->getIsGuest() && user()->getId() != $model->getOwnerId()): ?>
<!--                            -->
<!--                            <a class="button user-op-glean"-->
<!--                               data-object-type="blog"-->
<!--                               data-object-id="--><?php //echo $model->primaryKey ;?><!--"-->
<!--                               data-action-url="--><?php //echo Yii::app()->createUrl('/user/glean') ;?><!--"-->
<!--                                >-->
<!--                                收藏-->
<!--                            </a>-->

                                <?php
                                $this->widget('widgets.YsGleanWidget',array(
                                    'objectType'=>'blog',
                                    'objectId'=>$model->primaryKey,
                                    'actionConfirmation'=>'确定收藏该博文？',
                                ));
                                ?>
                            <?php endif ; ?>
                        </div>


                        <div id="comments" class="cell ">
                            <?php if($model->commentCount>=1): ?>
                                <h3>
                                    <?php echo $model->commentCount>1 ? $model->commentCount . ' comments' : 'One comment'; ?>
                                </h3>

                                <?php $this->renderPartial('_comments',array(
                                    'post'=>$model,
                                    'comments'=>$model->comments,
                                )); ?>
                            <?php endif; ?>

                            <h3>Leave a Comment</h3>

                            <?php if(Yii::app()->user->hasFlash('commentSubmitted')): ?>
                                <div class="flash-success">
                                    <?php echo Yii::app()->user->getFlash('commentSubmitted'); ?>
                                </div>
                            <?php else: ?>
                                <?php $this->renderPartial('/comment/_form',array(
                                    'model'=>$comment,
                                )); ?>
                            <?php endif; ?>

                        </div><!-- comments -->

                    </div>
                    <?php YsPageBox::endPanel(); ?>
            </div>


        </div>

</div>
<div>
    more like this :
    <?php

    $resultSet = $model->moreLikeThis() ;

    if($resultSet->getTotalHits()>0): ?>
    <?php   foreach($resultSet as $result): ?>
            <?php echo CHtml::link(CHtml::encode($result->title),
                Post::createPostViewUrl($result->id,$result->title),
                array('target'=>'_blank')); ?>
     <?php  endforeach ;   ?>
    <?php endif; ?>
</div>
<script type="text/javascript">

</script>