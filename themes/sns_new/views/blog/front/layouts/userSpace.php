<?php $this->beginContent(UserHelper::getUserBaseLayoutAlias('userSpaceContent'));  ?>

    <!--这里是顶部上下文菜单-->
<?php
if (!user()->getIsGuest()):
    $this->beginClip('top_context_menu');
    ?>
    <ul class="links nav">

        <li class="">
            <a href="<?php echo $this->createUrl('/blog/my/create'); ?>" class="">创建博文</a>
        </li>
        <li class="">
            <a href="<?php echo $this->createUrl('/blog/member/list', array('u' => Yii::app()->user->getId())); ?> ">我的博文</a>
        </li>

    </ul>
    <?php
    $this->endClip();
endif;
?>



    <!--         这里是内容区块-->

    <div class="cell">

        <div class="col">
            <div class="col width-7of9">
                <?php  echo $content; ?>
            </div>

            <div class="col width-fill">
                <div class="cell">
                    <?php UserHelper::renderSimpleProfile(UserHelper::getSpaceOwnerModel()); ?>
                </div>
                <div class="cell">
                    <div class="col">

                        <?php if (UserHelper::getIsOwnSpace()): ?>
                            <div class="menu cell">
                                <ul class="left links nav">
                                    <li class="">
                                        <a href="<?php echo $this->createUrl('/blog/category/admin'); ?>"
                                           class="">分类管理</a>
                                    </li>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <div class="cell menu">
                            <script type="text/javascript">
                                $(function () {
                                    var url = "<?php echo $this->createUrl('category/ajaxMyCategories',array('userId'=>UserHelper::getSpaceOwnerId())); ?>";
                                    $("#myBlogCategories").load(url);
                                });
                            </script>
                            <ul class="stat left nav" id="allBlogOfMember">
                                <li class="">
                                    <a href="<?php echo $this->createUrl('/blog/member/list', array('u' => UserHelper::getSpaceOwnerId())); ?>"
                                       class="">所有日志</a>
                                </li>
                            </ul>
                            <ul class="stat left nav" id="myBlogCategories">

                            </ul>
                        </div>
                    </div>
                </div>

                <div class="cell">
                    <?php
                    // 最近评论中视图可以自定义哦！
                    Yii::setPathOfAlias('blogLayouts',dirname(__FILE__));
                    $this->widget(
                        'application.modules.comment.widgets.LastCommentsWidget',
                        array(
                            'model' => 'Post',
                            'modelOwnerId' => UserHelper::getSpaceOwnerId(),
                            'view'=>'blogLayouts.comment.spaceLastComments',
                        )
                    );
                    ?>
                </div>

            </div>
        </div>

    </div>

<?php $this->endContent(); ?>