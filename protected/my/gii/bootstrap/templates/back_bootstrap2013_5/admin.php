<?php
/**
 * The following variables are available in this template:
 * - $this: the BootCrudCode object
 */
?>
<?php echo "<?php " ;?> PublicAssets::registerScriptFile('js/batch_gridview.js') ?>
<?php
echo "<?php\n";
$label = $this->pluralize($this->class2name($this->modelClass));
echo "\$this->breadcrumbs=array(
	//'$label'=>array('index'),
	Yii::t('backend', 'Manager').Yii::t('backend','$label'),
);\n";
?>

$this->menu=array(
//array('label'=>'List <?php echo $this->modelClass; ?>','url'=>array('index')),
array('label'=>Yii::t('backend', 'Create <?php echo $this->modelClass; ?>'),'url'=>array('create'),
    'linkOptions'=>array('target'=>'_blank'),
    'icon' => 'fa fa-plus'),
);

Yii::app()->clientScript->registerScript('search', "
    $('.search-button').click(function(){
    $('.search-form').toggle();
    $('.search-button').toggleClass('desc');
    return false;
});
    $('.search-form form').submit(function(){
    $('#<?php echo $this->class2id($this->modelClass); ?>-items-view').yiiGridView('update', {
          data: $(this).serialize()
    });
    return false;
    });
    ");
?>
<?php echo "<?php echo CHtml::link('[ 搜索条件 <span class=\"caret\"></span> ]', '#', array('class' => 'search-button')); ?>";  ?>

<div class="search-form  uzb-bordered at-top" style="display:none">
    <?php echo "<?php \$this->renderPartial('_search',array(
	'model'=>\$model,
)); ?>\n"; ?>
</div><!-- search-form -->

<?php echo "<?php "; ?> echo CHtml::beginForm(); ?>

<?php echo "<?php "; ?>
$this->renderPartial('_gridView',array('model'=> $model));
?>

<div class="uzb-bordered at-bottom form-inline">
    <?php echo "<?php"; ?> echo CHtml::hiddenField('ids', '', array('class' => 'batch-op-targets')); ?>
    <?php echo "<?php"; ?>
    $this->widget('my.widgets.ECheckAllWidget', array(
    'id' => 'parent',
    'label' => '删？<span id="msg"></span>',
    'labelPosition' => 'after',
    'childrenSelector' => '.checkbox-column :checkbox[name]:not([name$="_all"]),.batch-op-item',
    'triggerFunctions'=>'js:[function(recalculateState){checkAllReCalculateFunction=recalculateState}]',
    'callback' => 'js:function (checkedValues,childrenCount) {
            $(".batch-op-targets").val(checkedValues.toString());
            if(checkedValues.length==0){$("#msg").html("");exit;}
            $("#msg").html("("+checkedValues.length+"/"+childrenCount+")");
    }',
    'labelOptions' => array('class' => 'checkbox')
    ));
    ?>
    <?php echo "<?php"; ?>  echo CHtml::ajaxSubmitButton('提交', array('batchDelete'), array(
        'beforeSend'=>'js:function(){
            return confirm("确定要删除？");
        }',
             'success' => 'js:batchOpSuccess',
            'dataType' => 'json',
        ),
        array(
            'class' => 'btn btn-small', // btn-primary| btn-success |btn-warning| btn-danger| btn-inverse|btn-info
        ));
     ?>
</div>

<?php echo "<?php"; ?> echo CHtml::endForm(); ?>

<?php
  /**
   * 在后台admin视图实现批处理 可以参考下面的
   * @see http://www.yiiframework.com/wiki/353/working-with-cgridview-in-admin-panel/
   * @see TbExtendedGridView
   * @see TbBulkActions
   * @see http://yii-booster.clevertech.biz/extended-grid.html 这个是booster中如何实现做 参考Bulk Actions 段落
   * =========================
   * for switch different view type you can refer the dolphin
   * @see http://demo.boonex.com/administration/profiles.php (try for free !)
   * switch different view type can be achieved by pass a viewType param (Simple|Extended|Geeky)
   */
?>