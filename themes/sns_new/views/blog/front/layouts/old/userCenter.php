<?php $this->beginContent('//layouts/main'); ?>
<?php  $currentUser = UserHelper::getLoginUserModel() ; ?>

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
                    <ul class="links nav">
                        <?php if(!user()->getIsGuest()): ?>
                            <li class="">
                                <a href="<?php echo $this->createUrl('/blog/my/create'); ?>" class="">创建博文</a>
                            </li>
                            <li class="">
                                <a href="<?php echo $this->createUrl('/blog/my'); ?> ">我的博文</a>
                            </li>
                        <?php endif; ?>

                    </ul>
                </div>
            </div>
        </div>
        <div class="col border-top">
            <div class="menu cell page-sub-menu ">
                <ul class="bottom nav">
                    <li class="">
                        <a href="#" class="">相册</a>
                    </li>
                    <li>
                        <a href="<?php echo $this->createUrl('/blog/my'); ?>">博文</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="container site-body">

        <div class="cell">

            <?php if(Layout::hasRegion('rightSideBar')): ?>

                <div class="col width-7of9">
                    <?php  YsPageBox::beginPanel(); ?>
                    <?php echo $content; ?>
                    <?php  YsPageBox::endPanel();?>
                </div>

                <div class="col width-fill">
                  <?php Layout::renderRegion('rightSideBar'); ?>
                </div>
                
            <?php else:?>

                <div class="col">
                    <?php  YsPageBox::beginPanel(); ?>
                    <?php echo $content; ?>
                    <?php  YsPageBox::endPanel();?>
                </div>

            <?php  endif; ?>

        </div>

    </div>

<?php $this->endContent(); ?>