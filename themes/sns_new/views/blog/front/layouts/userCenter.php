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



<?php $this->endContent(); ?>