<?php $this->beginContent('//layouts/found_main'); ?>
<div>
    <?php $profile =  $this->widget('user.widgets.profile.UserProfile',array(
    'user'=>$_GET['u'],  //we assume when access some one 's space we will always pass the param "u" to the $_GET
)); ?>
</div>
    <hr size="2px">
<div class="row">
    <section class="two columns">
        <?php
        if (!empty($this->menu)) :
            $this->widget("bootstrap.widgets.FounTabs", array('items' => $this->menu, 'type' => 'nice vertical hide-on-phones'));
        endif;
        ?>
    </section>
    <section class="eight columns ">

        <?php echo $content; ?>
    </section>
    <section class="two columns">
        <?php $profile->renderSidebar(); ?>
    </section>
</div>

<?php $this->endContent(); ?>