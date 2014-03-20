<?php
/**
 * The following variables are available in this template:
 * - $this: the BootCrudCode object
 */
?>
<div class="row-bordered">
    <?php
    echo "<?php\n";
    $nameColumn=$this->guessNameColumn($this->tableSchema->columns);
    $label=$this->pluralize($this->class2name($this->modelClass));
    echo "\$this->breadcrumbs=array(
        //'$label'=>array('index'),
        //\$model->{$nameColumn}=>array('view','id'=>\$model->{$this->tableSchema->primaryKey}),
        Yii::t('backend', 'Update $this->modelClass').'[ '.\$model->{$nameColumn}.' ]',
    );\n";
    ?>

    /*$this->menu=array(
        array('label'=>'Manage <?php echo $this->modelClass; ?>','url'=>array('admin')),
    );*/
    ?>

        <legend><?php echo "<?php echo Yii::t('backend', 'Update $this->modelClass').' ： '.\$model->{$nameColumn}; ?>"; ?></legend>
    <?php echo "<?php echo \$this->renderPartial('_form',array('model'=>\$model)); ?>"; ?>
</div>