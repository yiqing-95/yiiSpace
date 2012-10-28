<?php $this->beginContent('//layouts/main'); ?>

<div class="container-fluid">
    <div class="row-fluid">
        <div class="span3" >
            <div style="margin-left: 50px;">
                <?php $this->widget('user.widgets.sidebar.UserSidebar'); ?>
            </div>

            <!--Sidebar content-->
        </div>
        <div class="span9">
            <?php echo $content; ?>
            <!--Body content-->
        </div>
    </div>
</div>

<?php $this->endContent(); ?>