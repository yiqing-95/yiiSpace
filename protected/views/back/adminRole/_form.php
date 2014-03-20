<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'admin-role-form',
    'enableAjaxValidation' => false,
    'type' => 'horizontal',
)); ?>

<p class="help-block">Fields with <span class="required">*</span> are required.</p>

<?php echo $form->errorSummary($model); ?>

<?php echo $form->textFieldRow($model, 'name', array('class' => 'span5', 'maxlength' => 50)); ?>

<?php echo $form->textAreaRow($model, 'description', array('rows' => 6, 'cols' => 50, 'class' => 'span8')); ?>

<?php
echo $form->radioButtonListInlineRow($model, 'disabled', array(
    0 => Yii::t('base', 'False'),
    1 => Yii::t('base', 'True')
));
?>

<?php // echo $form->textFieldRow($model, 'create_time', array('class' => 'span5', 'maxlength' => 10)); ?>

<?php // echo $form->textFieldRow($model, 'update_time', array('class' => 'span5', 'maxlength' => 10)); ?>
<div class="control-group">
    <?php echo $form->labelEx($model, 'purviews', array('class' => 'control-label')); ?>
    <div class="controls">
        <?php echo $form->hiddenField($model, 'purviews',array('class'=>'menuTree')); ?>
        <ul id="purviewTree" class="ztree span5" style=" height: 300px;  overflow-x: auto; overflow-y: scroll; background: #fff">

        </ul>
        <?php echo $form->error($model, 'purviews', array('class' => 'help-inline error')); ?>
    </div>

</div>

<div class="form-actions">
    <?php

    $this->widget('bootstrap.widgets.TbButton', array(
    'buttonType' => 'submit',
    'type' => 'primary',
    'label' => $model->isNewRecord ? 'Create' : 'Save',
)); ?>
</div>
<div>
    <?php
    print_r($model->getMenuIds());
    $adminTreeArray =  $model->isNewRecord ? AdminMenu::getAdminMenuTreeArray() : AdminMenu::getAdminMenuTreeArray4role($model);
    ?>
</div>
<?php $this->endWidget(); ?>

<script type="text/javascript">
    /**
     * 权限树
     */
    var zTreeSetting = {
        check: {
            enable: true,
            chkboxType: { "Y" : "ps", "N" : "ps" }
        },
        data: {
            simpleData: {
                enable: true
            }
        },
        callback: {
            onCheck: function(e, treeId, treeNode) {
                set_purviews();
            }
        }
    };

    var zTreeDatas =  <?php echo CJSON::encode($adminTreeArray); ?>;
    var zTreeObj;
    //格式化显示
    function formatterDisabled(value) {
        return (value == 0)?'<?php echo Yii::t('base', 'False') ?>':'<?php echo Yii::t('base', 'True') ?>';
    }

    //表单
    //设置权限
    function set_purviews() {
        nodes = zTreeObj.getCheckedNodes(true);
        purviews = [];
        $.each( nodes, function(i, n){
            purviews[i] = n.id;
        });
        $('.menuTree').val(purviews.toString());
    }


    $(document).ready(function(){
        //重置表单
        zTreeObj = $.fn.zTree.init($("#purviewTree"), zTreeSetting, zTreeDatas);
        // 树加载完毕后 设置下隐藏域的值
        set_purviews();

    });
</script>
