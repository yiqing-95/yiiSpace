<?php
/**
 * Created by PhpStorm.
 * User: yiqing
 * Date: 14-1-6
 * Time: 上午1:27
 */

class FriendCategoryWidget extends YsAjaxWidget{

    /**
     * @var int
     */
    public $userId ;


    /**
     * @return array
     */
    public function getOptions(){
        return array(
            'userId'=>$this->userId ,
        );
    }


    public function run(){
        if($this->isAjaxRequest){
            $cateList = RelationshipCategory::model()->findAllByAttributes(
                array(
                    'user_id'=>$this->userId,
                )
            );
           $this->render('_cateList',array(
               'cateList'=>$cateList,
           ));

        }else{
            $this->render('_friendCategory');
        }
    }
} 