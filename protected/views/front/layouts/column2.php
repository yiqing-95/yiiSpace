<?php $this->beginContent('//layouts/main'); ?>
<div class="container-fluid">
    <div class="row-fluid">

        <div class="span10">
            <?php echo $content; ?>
            <!--Body content-->
        </div>

        <div class="span2">
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
            <!--Sidebar content-->
        </div>
    </div>
</div>

<?php $this->endContent(); ?>