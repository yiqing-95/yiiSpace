<?php
/**
 * Created by PhpStorm.
 * User: yiqing
 * Date: 13-11-26
 * Time: 上午1:46
 * To change this template use File | Settings | File Templates.
 */

interface ITypeSearchHandler {

    /**
     * @return EsDataProvider|CDataProvider
     */
    public function doSearch();

    /**
     * @param null $data  callable method  will be used by TypeSearchListView
     * the data contain these info :
            $data=CListView::viewData;
            $data['index'] = $i;
            $data['data'] = $item;
            $data['widget'] = $CListView;

     * @param bool $return
     * @return mixed
     */
    public function renderItem($data=null,$return=false);
}