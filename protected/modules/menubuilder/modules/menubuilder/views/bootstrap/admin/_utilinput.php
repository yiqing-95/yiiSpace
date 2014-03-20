<!-- begin utilform -->
<div class="emb-utilinput">
    <?php
    //------------------------ flush the cache ----------------------------------------------
    if ($this->getModule()->isCacheInstalled() && $this->utilActionAllowed('flushcache'))
    {
        $confirmMessage = Yii::t('MenuBuilderModule.messages', 'Are you sure to flush the cache?');
        $link = CHtml::link(Yii::t('MenuBuilderModule.messages', 'Flush the menu cache'), $this->createUrl('flushcache'),
            array('onclick' => "if(!confirm('$confirmMessage')) return false;"));
        echo CHtml::tag('div', array(), $link);
    }
    //------------------------ save as default ----------------------------------------------
    if ($this->utilActionAllowed('saveasdefault'))
    {
        $confirmMessage = Yii::t('MenuBuilderModule.messages', 'Are you sure to save current menus as default?');
        $link = CHtml::link(Yii::t('MenuBuilderModule.messages', 'Save all menus as default'), $this->createUrl('saveasdefault'),
            array('onclick' => "if(!confirm('$confirmMessage')) return false;"));
        echo CHtml::tag('div', array(), $link);
    }
    //------------------------ restore default ----------------------------------------------
    if ($this->utilActionAllowed('restoredefault'))
    {
        $confirmMessage = Yii::t('MenuBuilderModule.messages', 'Are you sure to restore all menus from default?');
        $link = CHtml::link(Yii::t('MenuBuilderModule.messages', 'Restore all menus from default'), $this->createUrl('restoredefault'),
            array('onclick' => "if(!confirm('$confirmMessage')) return false;"));
        echo CHtml::tag('div', array(), $link);
    }
    //------------------------ reinstall ----------------------------------------------
    if ($this->utilActionAllowed('reinstall'))
    {
        $confirmMessage = Yii::t('MenuBuilderModule.messages', 'Are you sure to reinstall all menus?');
        $link = CHtml::link(Yii::t('MenuBuilderModule.messages', 'Reinstall all menus'), $this->createUrl('reinstall'),
            array('onclick' => "if(!confirm('$confirmMessage')) return false;"));
        echo CHtml::tag('div', array(), $link);
    }
    //------------------------ export ----------------------------------------------
    if ($this->utilActionAllowed('export'))
    {
        $link = CHtml::link(Yii::t('MenuBuilderModule.messages', 'Export menus'), $this->createUrl('export'));
        echo CHtml::tag('div', array(), $link);
    }
    ?>
    <?php if ($this->utilActionAllowed('import')): ?>
        <div>
            <?php echo CHtml::label(Yii::t('MenubuilderModule.messages', 'Import menus'),''); ?>
            <span class="">
                <?php
                echo CHtml::fileField(EMBConst::FIELDNAME_IMPORT,'',array('accept'=>'.zip'));
                $formAction=$this->createUrl('import');
                $formId=EMBConst::FORMID;
                $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'label' => Yii::t('MenubuilderModule.messages', 'Import'),
                    'htmlOptions' => array('onclick' => "$('#$formId').attr('action','$formAction');")));
                ?>
            </span>
        </div>
    <?php endif; ?>
</div>
<!-- end utilform -->