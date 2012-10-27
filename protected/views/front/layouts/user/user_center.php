<?php $this->beginContent('//layouts/found_main'); ?>

<div class="four columns" role="complementary" >
   <?php $this->widget('user.widgets.sidebar.UserSidebar'); ?>
</div>
<div class="six columns ">

    <?php echo $content; ?>
</div>
<div class="two columns">
    <?php
    if (!empty($this->menu)) :
        $this->widget("bootstrap.widgets.FounTabs", array('items' => $this->menu, 'type' => 'nice vertical hide-on-phones'));
    endif;
    ?>
</div>

<?php $this->endContent(); ?>