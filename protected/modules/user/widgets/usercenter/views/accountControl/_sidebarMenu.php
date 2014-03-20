<div class=" panel cell">
    <?php if(Layout::hasRegion('accountMenu.top')): ?>
                <?php Layout::renderRegion('accountMenu.top'); ?>
    <?php endif; ?>

    <?php CascadeFr::beginCollapsible();    ?>
    <div class="cell">
        <div class="menu ">
            <ul class="left links nav">
                <li class="disabled">设置
                    <ul class="right links nav">
                        <li class="">
                            <a href="<?php echo Yii::app()->createUrl( '/user/settings/photo'); ?>" class="">
                                图像
                            </a>
                        </li>
                        <li class="">
                            <!-- 用class=active来高亮当前菜单-->
                            <a href="#" class="">昵称</a>
                        </li>
                        <li class="">
                            <a href="#" class="">邮箱</a>
                        </li>
                        <li class="">
                            <a href="#" class="">密码</a>
                        </li>
                    </ul>
                </li>

            </ul>
            <ul class="left links nav">
                <li class="disabled">内容</li>
                <li class="">
                    <a href="#" class="">照片</a>
                </li>
                <li class="">
                    <a href="<?php echo Yii::app()->createUrl( '/blog/my'); ?>" class="">
                    博客
                    </a>
                </li>
<!--                -->
<!--                <li class="active">-->
<!--                    <a href="#" class="">Active item</a>-->
<!--                </li>-->
<!--               -->
            </ul>
        </div>
    </div>

    <?php CascadeFr::endCollapsible() ;?>

    <?php if(Layout::hasRegion('accountMenu.bottom')): ?>
        <?php Layout::renderRegion('accountMenu.bottom'); ?>
    <?php endif; ?>


</div>

