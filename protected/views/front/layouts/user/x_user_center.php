<?php $this->beginContent('//layouts/main'); ?>

<div class="container-fluid">
    <div class="row-fluid">
        <div class="span3">

            <!--Sidebar content-->
            <div class="alert alert-info span12">
                <?php $userProfile = $this->widget('user.widgets.usercenter.UserCenterProfile'); ?>
                <br/>
                <?php $userProfile->renderSidebarMenu(); ?>
            </div>

        </div>
        <div class="span9">
            <?php echo $content; ?>
            <!--Body content-->
        </div>
    </div>
</div>

<?php $this->endContent(); ?>