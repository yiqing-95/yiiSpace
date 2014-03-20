<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-10-31
 * Time: 上午10:45
 * To change this template use File | Settings | File Templates.
 */
class PhotoCommendBlock extends CWidget
{

    public function run(){
        /*
        $criteria = new CDbCriteria();
        $criteria->order = 't.created DESC';
        $criteria->limit = 15 ;
        $models = Status::model()->with('owner','image','link','video')->findAll($criteria);
        */
        Yii::import('photo.models.*');

        $dataProvider = Photo::listRecentPhotos();
        $dataProvider->getPagination()->setPageSize(8);

        $this->render('commend',array('photo'=>$dataProvider->getData()));

    }
}
