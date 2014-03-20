<?php
$tableName = $_GET['tableName'] ;
$tableClassName = YiiUtil::tableName2className($tableName);
echo "<?php  \$model = {$tableClassName}::model(); ?>";
?>

<table class="items">
    <thead>
    <tr>
        <?php foreach ($columns as $column) : ?>
        <th>
         <?php  echo "<?php echo CHtml::encode(\$model->getAttributeLabel('{$column}')); ?> \n "; ?>
        </th>
        <?php endforeach; ?>
    </tr>
    </thead>

    <tbody>

    <?php
      $phpCode = <<<PHP
    <?php \$this->widget('zii.widgets.CListView', array(
             'dataProvider' => \$dataProvider,
             'itemView' => '_view',
         ));
     ?>
PHP;
    echo $phpCode ;
    ?>

    </tbody>
</table>

<hr>
<p>_view 文件的内容：</p>
<tr class="view">
    <?php foreach ($columns as $col): ?>
    <td>
        <?php echo  "<?php echo CHtml::encode(\$data->{$col}); ?> \n ";?>
    </td>
    <?php endforeach;?>
</tr>
    <br/>