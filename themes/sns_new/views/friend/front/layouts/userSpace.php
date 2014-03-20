<?php $this->beginContent(UserHelper::getUserBaseLayoutAlias('userSpaceContent')); ?>

    <!--这里是顶部上下文菜单-->
<?php
if (!user()->getIsGuest()):
    $this->beginClip('top_context_menu');
    ?>
    <ul class="links nav">

        <li class="">
            <a href="<?php echo $this->createUrl('/album/create'); ?>" class="">创建相册</a>
        </li>
        <li class="">
            <a href="<?php echo $this->createUrl('/album/member', array('u' => Yii::app()->user->getId())); ?> ">我的相册</a>
        </li>

    </ul>
    <?php
    $this->endClip();
endif;
?>



    <!--         这里是内容区块-->

    <div class="cell">

        <div class="col">

                <?php echo $content; ?>

        </div>

    </div>

<?php $this->endContent(); ?>