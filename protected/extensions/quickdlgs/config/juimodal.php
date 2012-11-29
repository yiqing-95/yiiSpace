<?php
/**
 * This are the default attributes used to initialize a E(Base)JuiDlg widget
 * Modify this to set the default height, width ...
 *
 * The 'dialogAttributes' array are the attributes for the CJuiDialog widget
 * @link http://www.yiiframework.com/doc/api/1.1/CJuiDialog
 *
 * @see the methods of the class EQuickDlgs
 *
 */

return array(
    'dialogTitle' => '',
    'dialogWidth' => 580,
    'dialogHeight' => 480,
    'openButtonType' => 'button', //link
    'openButtonText' => 'Open dialog',
    'openButtonHtmlOptions' => array(),
    'openImageUrl' => null, //if set, an image instead a button will be rendered
    'renderOpenButton' => true,
    'closeButtonText' => '', //set a value if you want to add a closeButton to the dialog
    'contentWrapperTag' => 'div',
    'contentWrapperHtmlOptions' => array(),
    'jsBeforeOpenDialog' => null,
    'jsAfterOpenDialog' => null,

    //set the attributes of the CJuiDialog widget
    'dialogAttributes' => array(
        //'theme' => 'cupertino',
        //'themeUrl' => '....',
        //'cssFile' => '....',
        //'skin' => '....',
        'options' => array(
            'autoOpen' => false,
            'modal' => true,
            'buttons' => array(),
        )
    )
);

