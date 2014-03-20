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
<?php Layout::endBlock() ;?>


<div class="col">
        col
        <div class="col">
            <div class="col width-4of5">

                    <?php YsPageBox::beginPanel() ;?>
                    <div class="cell">

                        <?php $this->renderPartial('_view', array(
                            'data'=>$model,
                        )); ?>

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

            <div class="col width-1of5">
                <div class="cell">
                    <?php UserHelper::renderSimpleProfile(UserHelper::getSpaceOwnerModel()) ;?>
                </div>
                <?php if(UserHelper::getIsOwnSpace()): ?>
                    <div class="menu cell">
                        <ul class="right nav">
                            <li class="">
                                <a href="<?php echo $this->createUrl('my/update',array('id'=>$model->primaryKey)); ?>" class="">
                                修改
                                </a>
                            </li>
                        </ul>
                    </div>
                <?php endif;?>

                <div class="cell">
                    <div class="col">

                        <?php if(UserHelper::getIsOwnSpace()): ?>
                        <div class="menu cell">
                            <ul class="left links nav">
                                <li class="">
                                    <a href="<?php echo $this->createUrl('/blog/category/admin'); ?>" class="">分类管理</a>
                                </li>
                            </ul>
                        </div>
                        <?php endif; ?>

                        <div class="cell menu">
                            <script type="text/javascript">
                                $(function(){
                                    var url = "<?php echo $this->createUrl('category/ajaxMyCategories',array('userId'=>$model->author_id)); ?>";
                                    $("#myBlogCategories").load(url);
                                });
                            </script>
                            <ul class="stat left nav" id="allBlogOfMember">
                                <li class="">
                                    <a href="<?php echo $this->createUrl('/blog/member',array('u'=>UserHelper::getSpaceOwnerId()) ); ?>" class="">所有日志</a>
                                </li>
                            </ul>
                            <ul class="stat left nav" id="myBlogCategories">

                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>

</div>
