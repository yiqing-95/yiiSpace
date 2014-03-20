<?php $this->beginContent(UserHelper::getUserBaseLayoutAlias('userCenterContent')); ?>
<?php  $currentUser = UserHelper::getLoginUserModel() ; ?>

            <?php if(Layout::hasRegion('rightSideBar')): ?>

                <div class="col width-7of9">

                    <?php echo $content; ?>

                </div>

                <div class="col width-fill">
                  <?php Layout::renderRegion('rightSideBar'); ?>
                </div>
                
            <?php else:?>

                <div class="col">

                    <?php echo $content; ?>

                </div>

            <?php  endif; ?>

<!--输出额外的box给主用户中心布局-- >
    <?php Layout::beginBlock('rightSideBox'); ?>
<?php  YsPageBox::beginPanel(array('template' => '{header}{body}', 'header' => '最近访客')); ?>
    <div class="cell">
        <?php  $this->widget('user.widgets.4cascadeFr.latestVisitors.LatestVisitors', array(
            'spaceId' => user()->getId(),
            'maxCount' => 9,
        ));  ?>

    </div>
<?php  YsPageBox::endPanel();?>
    <?php  Layout::endBlock() ?>
    <!--输出额外的box给主用户中心布局/-->

<?php $this->endContent(); ?>