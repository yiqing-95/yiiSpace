<?php $this->beginContent('//layouts/found_main'); ?>

<section class="two columns">

</section>
<section class="eight columns ">

    <?php echo $content; ?>
</section>
<section class="two columns">
    <?php
    if (!empty($this->menu)) :
        $this->widget("foundation.widgets.FounTabs", array('items' => $this->menu, 'type' => 'nice vertical hide-on-phones'));
    endif;
    ?>
</section>

<?php $this->endContent(); ?>