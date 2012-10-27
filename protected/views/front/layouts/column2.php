<?php $this->beginContent('//layouts/main'); ?>

<section class="two columns">

</section>
<section class="eight columns ">

    <?php echo $content; ?>
</section>
<section class="two columns">
    <?php
    $this->beginWidget('zii.widgets.CPortlet', array(
        'title'=>'Operations',
    ));
    $this->widget('zii.widgets.CMenu', array(
        'items'=>$this->menu,
        'htmlOptions'=>array('class'=>'operations'),
    ));
    $this->endWidget();
    ?>
</section>

<?php $this->endContent(); ?>