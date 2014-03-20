<?php

class SearchController extends Controller
{
    /**
     * @var string
     */
    public $defaultAction = 'do';

    /**
     * 此处可以使用的策略太多了
     * 比如 同一个search布局 就搜索结果列表部分是变动的
     * 或者...
     */
    public function actionDo()
	{

        $dataProvider = null ;
        $typeSearchHandler = null ;

        if(isset($_GET['q'])){
            if(isset($_GET['type'])){
                $type = $_GET['type'];
                $typeSearchHandler = AppComponent::typeSearchManager()->getSearchHandler($type);
                $dataProvider =  $typeSearchHandler->doSearch() ;
            }
        }

		$this->render('do',array(
            'dataProvider'=>$dataProvider,
            'typeSearchHandler'=>$typeSearchHandler,
        ));
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