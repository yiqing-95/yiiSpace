<?php $this->beginContent('//layouts/main'); ?>

<section class="two columns">

</section>
<section class="eight columns ">

    <?php echo $content; ?>
</section>
<section class="two columns">
    <?php
    if (!empty($this->menu)) :
        $this->widget("bootstrap.widgets.TbMenu", array('items' => $this->menu, 'type' => 'pills'));
    endif;
    ?>
</section>

<?php $this->endContent(); ?>