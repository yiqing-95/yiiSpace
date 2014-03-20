
<?php
$this->beginWidget('YsPageBox',array(
    'template'=>'{header}{body}{footer}',
));
?>
<div class="cell">
    hhhhhhh
</div>
<?php $this->endWidget(); ?>


<?php
$this->beginWidget('YsPageBox',array(
    'template'=>'{header}{body}{footer}',
    'freeBody'=>true ,
));
?>
<div class="body">

    <div class="cell">
        body1
    </div>
</div>

<div class="body">

    <div class="cell">
        body2
    </div>
</div>


<?php $this->endWidget(); ?>



