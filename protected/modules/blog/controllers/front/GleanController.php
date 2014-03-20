<?php
/**
 * Created by PhpStorm.
 * User: yiqing
 * Date: 13-12-31
 * Time: 上午11:22
 */

class GleanController extends BaseBlogController {

    public function actionList(){
        $this->layout = UserHelper::getUserBaseLayoutAlias('userSpaceGlean');

        $model=new Post('search');
        // 默认给一个收藏类型 在下面动态覆盖！

        $model->unsetAttributes();  // clear any default values
        // 关联查询下收藏的对象 动态关联 存在跨模块访问的问题！
        $model->getDbCriteria()->with = array(
            'glean'=>array(

            )
        );
        $model->getDbCriteria()->addCondition('glean.user_id=:gleanerId');
        $params = $model->getDbCriteria()->params ;
        $model->getDbCriteria()->params = CMap::mergeArray($params,array(
           ':gleanerId'=> user()->getId(),
        ));


        $this->render('list',array('model'=>$model));
    }
} 