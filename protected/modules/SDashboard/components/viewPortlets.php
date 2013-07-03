<?php

class viewPortlets extends CWidget {
	public $mode = "view";
  public $userid = 0;
    /**
     * Publishes the required assets
     */

    public function init() {
        parent::init();
        $this->publishAssets();
    }

    public function run() {
    $dataProvider = Dashboard::findPortlets($this->userid);
    switch($this->mode){
      case 'view':
      $itemView = '_viewportlets';
      break;
      }
	     $this->widget('bootstrap.widgets.BootThumbnails', array(
                'dataProvider'=>$dataProvider,
                'viewData'=>array(),
                'template'=>"{items}",
                'itemView'=>$itemView,
        )); 
    }
    /**
     * Publises and registers the required CSS and Javascript
     * @throws CHttpException if the assets folder was not found
     */
    public function publishAssets() {
       $assets =Yii::app()->basePath.'/modules/sdashboard/assets';
        $baseUrl = Yii::app() -> assetManager -> publish($assets);
        //the css to use
        Yii::app() -> clientScript -> registerCssFile($baseUrl . '/css/sdashboard.css');
    }

}
