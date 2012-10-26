<?php

class CssController extends Controller
{

    /**
     * @Desc("下啦菜单 可用的还有2 + test/2 ")
     */
    public function actionDropDownNav($test='')
    {
        $this->render('dropDownNav'.$test);
    }
    /**
     * @Desc("纵、竖向导航")
     */
    public function actionVerticalNav()
    {
        $this->render('verticalNav');
    }
    /**
     * @Desc("一列定宽居中")
     */
    public function actionOneColumnCenter()
    {
        $this->render('oneColumnCenter');
    }

    /**
     * @Desc("测试下横向导航的设计")
     */
    public function actionIndex()
	{
		$this->render('index');
	}

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}