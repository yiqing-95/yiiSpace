<?php
/**
 * The following variables are available in this template:
 * - $this: the BootCrudCode object
 */
?>
<div class="row-bordered">
    <?php
    echo "<?php\n";
    $label=$this->pluralize($this->class2name($this->modelClass));
    echo "\$this->breadcrumbs=array(
        //'$label'=>array('index'),
        Yii::t('backend', 'Create $this->modelClass'),
    );\n";
    ?>

    /*$this->menu=array(
        array('label'=>'List <?php echo $this->modelClass; ?>','url'=>array('index')),
        array('label'=>'Manage <?php echo $this->modelClass; ?>','url'=>array('admin')),
    );*/
    ?>
    <legend><?php echo "<?php echo Yii::t('backend', 'Create $this->modelClass') ;?>";?></legend>

    <?php echo "<?php echo \$this->renderPartial('_form', array('model' => \$model)); ?>"; ?>
</div>
