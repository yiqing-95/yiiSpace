<?php

Yii::import('group.models._base.BaseGroupTopic');

class GroupTopic extends BaseGroupTopic
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function relations()
    {
        return array(
            'group'=>array(self::BELONGS_TO,'Group','group_id'),
        );
    }

    /**
     * 用来缓存同一个请求域中的topic对象
     *
     * @var array
     */
    protected static  $tempTopics = array() ;

    /**
     * 查看某个topic时 先要加载这个topic对象
     * 在树形信息结构中 往往需要记载父类路径
     * （比如用来构成 "面包屑"|当前位置|breadcrumbs）
     *
     * @param $topicId
     * @return mixed|GroupTopic
     *
     * 附带关系查询时只查询用到的字段可以提升性能
     * 但有时也不那么太必须（比较懒）！
     */
    public static  function getTopic4View($topicId){
        $tempCacheKey = 'topic4view_'.$topicId ;
        if(!isset(self::$tempTopics[$tempCacheKey])){
            $criteria = new CDbCriteria();
            $criteria->with = array(
                'group'
            );

            self::$tempTopics[$tempCacheKey] = self::model()->findByPk($topicId ,$criteria);
        }
        return self::$tempTopics[$tempCacheKey] ;
    }
}