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
        <div class="span6">
            <?php UserHelper::getUserPublicProfile()->renderUserTopMenus(0) ;?>
            <?php echo $content; ?>
            <!--Body content-->
        </div>
        <div class="span2"  style="border-left: 1px solid #d935cc ;margin-left: 20px; padding: 30px;">
            <div class="row-fluid span12">
                <h3>最近来访：</h3>
                <ul class="thumbnails">
                    <li class="span4">
                        <a href="#" class="thumbnail">
                            <img src="<?php echo bu('public/default/user/avatars/1.gif');?>" alt="">
                        </a>
                    </li>
                    <li class="span4">
                        <a href="#" class="thumbnail">
                            <img src="<?php echo bu('public/default/user/avatars/1.gif');?>" alt="">
                        </a>
                    </li>
                    <li class="span4">
                        <a href="#" class="thumbnail">
                            <img src="<?php echo bu('public/default/user/avatars/1.gif');?>" alt="">
                        </a>
                    </li>
                    <li class="span4">
                        <a href="#" class="thumbnail">
                            <img src="<?php echo bu('public/default/user/avatars/1.gif');?>" alt="">
                        </a>
                    </li>
                </ul>
            </div>

            <!--Sidebar content-->
            <div class="row-fluid span12">
                <h3>可能认识：</h3>
                <ul class="thumbnails">
                    <li class="span4">
                        <a href="#" class="thumbnail">
                            <img src="<?php echo bu('public/default/user/avatars/1.gif');?>" alt="">
                        </a>
                    </li>
                    <li class="span4">
                        <a href="#" class="thumbnail">
                            <img src="<?php echo bu('public/default/user/avatars/1.gif');?>" alt="">
                        </a>
                    </li>
                </ul>
            </div>

            <div class="row-fluid span12">
                <h3>在线好友：</h3>
                <ul class="thumbnails">
                    <li class="span4">
                        <a href="#" class="thumbnail">
                            <img src="<?php echo bu('public/default/user/avatars/1.gif');?>" alt="">
                        </a>
                    </li>
                    <li class="span4">
                        <a href="#" class="thumbnail">
                            <img src="<?php echo bu('public/default/user/avatars/1.gif');?>" alt="">
                        </a>
                    </li>
                </ul>
            </div>

            <div class="row-fluid span12">
                <h3>近期生日：</h3>
                <ul class="thumbnails">
                    <li class="span4">
                        <a href="#" class="thumbnail">
                            <img src="<?php echo bu('public/default/user/avatars/1.gif');?>" alt="">
                        </a>
                    </li>
                    <li class="span4">
                        <a href="#" class="thumbnail">
                            <img src="<?php echo bu('public/default/user/avatars/1.gif');?>" alt="">
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

<?php $this->endContent(); ?>