<!-- begin utilform -->
<div class="emb-utilinput">
    <?php
    //------------------------ flush the cache ----------------------------------------------
    if ($this->getModule()->isCacheInstalled() && $this->utilActionAllowed('flushcache'))
    {
        $confirmMessage = Yii::t('MenuBuilderModule.messages', 'Are you sure to flush the cache?');
        $link = CHtml::link(Yii::t('MenuBuilderModule.messages', 'Flush the cache'), $this->createUrl('flushcache'),
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
    <div class="row">
        <?php echo CHtml::fileField(EMBConst::FIELDNAME_IMPORT,'',array('accept'=>'.zip'));?>
        <?php
            $formAction=$this->createUrl('import');
            $formId=EMBConst::FORMID;
            echo CHtml::submitButton(Yii::t('MenubuilderModule.messages', 'Import menus'), array('name' => EMBConst::BUTTONNAME_DELETEITEM, 'onclick' => "$('#$formId').attr('action','$formAction');"));
        ?>
    </div>
    <?php endif; ?>
</div>
<!-- end utilform -->