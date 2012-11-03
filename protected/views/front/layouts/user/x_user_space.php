<?php $this->beginContent('//layouts/main'); ?>
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
            $this->widget("bootstrap.widgets.TbMenu", array('items' => $this->menu, 'type' => 'pills'));
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