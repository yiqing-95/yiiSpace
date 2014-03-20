<?php $this->beginContent('//layouts/main'); ?>
<?php $currentUser = UserHelper::getSpaceOwnerModel(); ?>

    <div class="container site-body ">
        <div class="col">
            <div class="size5of7 col">
                <div class="col">
                    <div class="col width-1of5 cell">
                        <img src="<?php echo UserHelper::getUserIconUrl($currentUser); ?>" width="64px" height="64px">
                    </div>
                    <div class="col width-fill">
                        <div class="menu cell">
                            <ul class="nav">
                                <li class=""><a href="#" class="">Normal item</a></li>
                                <li><a href="#">Another normal item</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="menu cell sizefill col">
                <div class="cell">
                    <?php
                    // 顶部上下文菜单区
                    if (isset($this->clips['top_context_menu'])): ?>
                        <?php echo $this->clips['top_context_menu']; ?>
                    <?php endif; ?>

                </div>
            </div>
        </div>
        <div class="col border-top">
            <div class="menu cell page-sub-menu ">
                <ul class="bottom nav">
                    <?php
                    $topNavList = YsNavSystem::getUserSpaceNav('top_nav');

                    foreach ($topNavList as $fromModule => $moduleMenuConfig):
                        foreach ($moduleMenuConfig as $menuKey => $menuConfig):
                            ?>

                            <li class="">
                                <?php
                                $menuConfig['url']['u'] = UserHelper::getSpaceOwnerId();
                                echo CHtml::link($menuConfig['text'], $menuConfig['url']);
                                ?>
                            </li>

                        <?php
                        endforeach;
                    endforeach;
                    ?>

                    <!--                    <li>-->
                    <!--                        <a href="-->
                    <?php //echo $this->createUrl('/blog/member/list',array('u'=> UserHelper::getSpaceOwnerId() )); ?><!-- ">博文</a>-->
                    <!--                    </li>-->

                </ul>
            </div>
        </div>
    </div>


    <div class="container site-body">

        <?php echo $content; ?>

    </div>
<?php $this->endContent(); ?>