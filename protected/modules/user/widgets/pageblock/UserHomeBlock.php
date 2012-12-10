<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-10-31
 * Time: 上午10:45
 * To change this template use File | Settings | File Templates.
 */
class UserHomeBlock extends HomePageBlock
{
    /**
     * @var string
     */
    public  $title = 'users';

    /**
     * @var string
     */
    public $activeAction = 'latest';


    public function init(){
        $this->tbBoxOptions['headerIcon'] = 'icon-user' ;
          // 'headerButtons'=>false, this can empty the headerButtons
    }

    /**
     * @return array
     */
    protected function getHeaderActions(){
     return  array(
           array('label'=>'tops', 'url'=>$this->createActionUrl('tops'), 'icon'=>'icon-music',
               'linkOptions'=>array('onclick'=>$this->onActionClick)),

           array('label'=>'latest', 'url'=>$this->createActionUrl('latest'), 'icon'=>'icon-headphones',
               'linkOptions'=>array('onclick'=>$this->onActionClick)),
           // '---',
       );
   }


    public function actionTops(){
        return __METHOD__ ;
    }

    public function actionLatest(){
        $criteria = new CDbCriteria();
        $criteria->order = 'create_at DESC';
        $criteria->limit = 15 ;
        $users = User::model()->findAll($criteria);

         $this->render('_latest',array('users'=>$users));
    }
}
