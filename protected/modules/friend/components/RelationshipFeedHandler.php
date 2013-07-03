<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-8-25
 * Time: 下午5:41
 * To change this template use File | Settings | File Templates.
 */
class RelationshipFeedHandler extends AbstractActionFeedHandler
{

    /**
     * @return string serialized data for render the corresponding template
     */
    public function getData()
    {
       return  parent::getData();
    }

    /**
     * @param $data
     * @return mixed
     */
    public function renderTitle($data)
    {
        $data = unserialize($data);
        echo "fellow user ",
        CHtml::link($data['user_b'],array('user/space','u'=>$data['user_b']));

    }
}
