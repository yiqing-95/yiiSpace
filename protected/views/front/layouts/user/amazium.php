<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">

    <title><?php echo CHtml::encode(Yii::app()->name); ?></title>
    <?php
    $this->widget('my.widgets.amazium.Amazium'); ?>

</head>

<body>
<?php echo $content ; ?>
</body>

</html>