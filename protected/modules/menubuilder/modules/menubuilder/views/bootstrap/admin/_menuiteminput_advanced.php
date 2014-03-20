<?php
/**
 * The form input for a menuitem without begin/end form
 */
?>
<?php
if ($this->menuitemFieldAllowed('active'))
    echo $form->checkBoxRow($model, 'active');

if ($this->menuitemFieldAllowed('descriptions'))
{
    echo $form->checkBoxRow($model, 'descriptionashint');
    $this->renderPartial('_languagesfields', array('form' => $form, 'model' => $model, 'attribute' => 'descriptions', 'labelText' => Yii::t('MenubuilderModule.messages', 'Description')));
}

if ($this->menuitemFieldAllowed('ajaxOptions'))
    echo $form->textFieldRow($model, 'ajaxOptions',
        array('class'=>'span6','hint'=>Yii::t('MenubuilderModule.messages', 'Separate with ; update=#ajaxContent;...')));

if ($this->menuitemFieldAllowed('linkOptions'))
    echo $form->textFieldRow($model, 'linkOptions',
        array('class'=>'span6','hint'=>Yii::t('MenubuilderModule.messages', 'Separate with ; class=link-class;confirm=Please confirm;...')));

if ($this->menuitemFieldAllowed('itemOptions'))
    echo $form->textFieldRow($model, 'itemOptions',
        array('class'=>'span6','hint'=>Yii::t('MenubuilderModule.messages', 'Separate with ; class=item-container;...')));

if ($this->menuitemFieldAllowed('submenuOptions'))
    echo $form->textFieldRow($model, 'submenuOptions',
        array('class'=>'span6','hint'=>Yii::t('MenubuilderModule.messages', 'Separate with ; class=submenu-container;...')));

if ($this->menuitemFieldAllowed('template'))
{
    $templates = $this->getModule()->itemTemplates;
    if (!empty($templates))
        echo $form->template($model, 'template',$templates, array('prompt' => '-', 'encode' => false));
    else
        echo $form->textFieldRow($model, 'template',array('class'=>'span6'));
}
