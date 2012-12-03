<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-10-31
 * Time: 上午10:45
 * To change this template use File | Settings | File Templates.
 */
class PhotoHomeBlock extends HomePageBlock
{
    /**
     * @var string
     */
    public  $title = '最新上传';

    /**
     * @var string
     */
    public $activeAction = 'latest';


    public function init(){
        $this->tbBoxOptions['headerIcon'] = 'icon-photo' ;
           $this->tbBoxOptions['headerActions'] = false; // this can empty the headerActions
    }

    /**
     * @return array
     */
    protected function getHeaderActions(){
     return  array(
          /*
           array('label'=>'tops', 'url'=>$this->createActionUrl('tops'), 'icon'=>'icon-music',
               'linkOptions'=>array('onclick'=>$this->onActionClick)),
            */
           array('label'=>'latest', 'url'=>$this->createActionUrl('latest'), 'icon'=>'icon-headphones',
               'linkOptions'=>array('onclick'=>$this->onActionClick)),
           // '---',
       );
   }


    public function actionTops(){
        return __METHOD__ ;
    }

    public function actionLatest(){
        /*
        $criteria = new CDbCriteria();
        $criteria->order = 't.created DESC';
        $criteria->limit = 15 ;
        $models = Status::model()->with('owner','image','link','video')->findAll($criteria);
        */
        $dataProvider = Photo::listRecentPhotos();
        $dataProvider->getPagination()->setPageSize(12);
        // $this->render('_latest',array('dataProvider'=>$dataProvider));
        foreach($dataProvider->getData() as $data){
            $data = Photo::model()->populateRecord($data);
           $this->render('_thumb',array('data'=>$data));
            //print_r($data);
        }
    }
}
