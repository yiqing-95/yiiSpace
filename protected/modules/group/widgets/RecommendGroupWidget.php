<?php
/**
 * Created by PhpStorm.
 * User: yiqing
 * Date: 14-1-29
 * Time: 下午2:04
 */



class RecommendGroupWidget extends YsWidget{

    /**
     * @var int
     */
    public $maxGroup = 10 ;


    public function run(){
        $criteria = new CDbCriteria();
        $criteria->compare('recommend_grade','>0');

        $criteria->limit = $this->maxGroup ;
        $criteria->addColumnCondition(array(
            'type'=>'public',
        ));

        $groupList = Group::model()->findAll($criteria);

        $this->render('recGroups',array(
            'groupList'=>$groupList,
        ));
    }
} 