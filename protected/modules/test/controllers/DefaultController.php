<?php

class DefaultController extends Controller
{

    /**
     * @var string
     */
    public $layout = 'test';


    /**
     * @Desc('测试语言选择')
     */
    public function actionLangPick() {
        Yii::import('test.extensions.LangPick.ELangPick');
        ELangPick::setLanguage();

       $text = $this->widget('test.extensions.LanguagePicker.ELangPick', array(
            'pickerType' => 'dropdown',          // buttons, links, dropdown
            'buttonsSize' => 'mini',            // mini, small, large
            'buttonsColor' => 'success',        // primary, info, success, warning, danger, inverse
        ),true);

        $this->renderText($text);
    }

	public function actionIndex()
	{

		$this->render('index');
	}

    public function actionFleetBox()
    {

        $this->render('fleetBox');
    }


    /**
     * @param string $id
     * @Desc('测试条形码生成')
     */
    public function actionGenerateBarcode($id='hello') {
        $bc = app()->BarcodeGenerator->init('png');
        $bc->build($id);
    }

    /**
     * @Desc('测试扩展accordion')
     */
    public function actionAccordion() {
        $this->render('accordion');
    }
    /**
     * @Desc('测试扩展lightBox')
     */
    public function actionLightBox() {
        $this->render('lightBox');
    }
}