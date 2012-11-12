<?php $this->beginContent('//layouts/_main'); ?>

    <div class="row-fluid">
        <div class="span1"></div>
        <div class="span2 ">
<!--        <div class="span2 offset1"> 早期的bootstrap 没有offset1 这个css类-->

            <!--Sidebar content-->
            <div class="alert alert-info span12">
                <?php $userProfile = $this->widget('user.widgets.usercenter.UserCenterProfile'); ?>
                <br/>
                <?php $userProfile->renderSidebarMenu(); ?>
            </div>

        </div>
        <div class="span8">
            <?php echo $content; ?>
            <!--Body content-->
        </div>

    </div>

<?php $this->endContent(); ?>