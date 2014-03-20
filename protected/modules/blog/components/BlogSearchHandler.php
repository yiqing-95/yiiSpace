<?php
/**
 * Created by PhpStorm.
 * User: yiqing
 * Date: 13-11-26
 * Time: 上午1:48
 * To change this template use File | Settings | File Templates.
 */

// 这个由于是跨模块调用 所以 先实例化本模块 导入必要的目录（参考init方法）
Yii::app()->getModule('blog');

class BlogSearchHandler extends CWidget implements  ITypeSearchHandler{

    /**
     * @return EsDataProvider|mixed
     */
    public function doSearch()
    {
        WebUtil::printCharsetMeta();
        $elastica_query = new Elastica\Query();
        //   $term_filter = new \Elastica\Filter\Term();
        // $term_filter->setTerm('name', 'Elastica_test');
        // $elastica_query->setFilter($term_filter);
        /*
         $dataprovider = new  ElasticaDataProvider('test', $elastica_query, array(
             'sort' => array(
                 'attributes' => array('attribute.desc',),
             ),
             'pagination' => array(
                 'pageSize' => 30,
             ),
         ), 'type_name_optional');

        $dataprovider = new  ElasticaDataProvider('blog', $elastica_query, array(

            'pagination' => array(
                'pageSize' => 130,
            ),
        ));
        */

        // 不能提前设置search 太蛋疼了
        // $search = new Elastica\Search();


        $dataProvider = new EsDataProvider(Post::model(),array());
        $search = $dataProvider->getSearch()->setQuery($_GET['q']);

        /*
        $data = $dataProvider->getData();
        print_r($data);

        // TODO: Implement doSearch() method.
        echo __METHOD__ ;
        */
        return $dataProvider ;
    }

    /**
     * @param null $data
     * @param bool $return
     * @return mixed|void
     * 也可以直接echo出结果（自己构造模板） 不借助view
     */
    public function renderItem($data=null,$return=false){
        //print_r($data);
        $this->render('search/_item',$data);
    }
}