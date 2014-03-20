<?php
/**
 * Returns the data from MOngodb in a data provider
 * Extend Yii's CDataProvider
 */
class ElasticaDataProvider0 extends CDataProvider{
    protected $_elastica_query;
    protected $_totalItemCount;
    protected $_elastica_index;
    protected $_elastica_result_set;
    protected $_sort;
    protected $_count;
    protected $type;


    public function __construct($index, $query, $config, $type=null) {
        if (!$type) {
            $type = $index;
        }
        $this->type = strtolower($type);
        $this->setId($type);
        $this->_elastica_query = $query;
        $client = Yii::app()->elastica->getClient();
        $this->_elastica_index = $client->getIndex($index);


        foreach($config as $key=>$value) {
            if ($key == 'sort') {
                $this->setSort($value);
                continue;
            }
            $this->{$key} = $value;

        }

    }

    protected function fetchData() {
        if (($sort=$this->getSort()) !== false) {
            foreach ($sort->attributes as  $attr) {
                $order = 'asc';
                $name = $attr;
                if (strstr($attr, '.desc')) {
                    $order = 'desc';
                    $name = str_replace('.desc', '', $attr);
                }

                $this->_elastica_query->setSort(array(
                    $name=>array('order' => $order, 'mode' => 'max'),

                ));
            }

        }
        if (($order = $sort->getOrderBy()) != '') {
            $order_array = explode(' ', $order);
            $name  = $order_array[0] ;
            $name = str_replace('[', '.', $name);
            $name = str_replace(']', '', $name);
            $descending = isset($order_array[1]) ? 'desc' : 'asc';
            $this->_elastica_query->setSort(array(
                $name=>array('order' => $descending, 'mode' => 'max'),

            ));
        }

        if (($pagination = $this->getPagination()) !== false) {
            $limit = $pagination->pageSize;
            $current_page = Yii::app()->request->getParam($this->getPagination()->pageVar, 0);
            $current_page++;

            $skip = --$current_page * $limit;
            $this->_elastica_query->setLimit($limit);
            $this->_elastica_query->setFrom($skip);
        } else {
            $this->_elastica_query->setLimit($this->_count);
        }

        $this->_elastica_result_set = $this->_elastica_index->search($this->_elastica_query);
        $this->_count = $this->_elastica_result_set->getTotalHits();

        if ($skip >= $this->_count) {
            $this->_elastica_query->setFrom(0);
            $this->getPagination()->setCurrentPage(0);
            $this->_elastica_result_set = $this->_elastica_index->search($this->_elastica_query);
        }

        if (($pagination = $this->getPagination()) !== false) {
            $pagination->setItemCount($this->getTotalItemCount());
        }

        $data = array();
        foreach ($this->_elastica_result_set->getResults() as $key => $value) {
            $type = ucfirst($this->type);
            $obj = new $type;
            $obj->setAttributes($value->getSource());
            $data[] = $obj;
        }

        $time = $this->_elastica_result_set->getResponse()->getQueryTime();
        if ($time) {
            Yii::log('Elastic: time took for Elastic Search Query is: ['.($time*100).' ms]'.PHP_EOL.json_encode($this->_elastica_query->toArray()),  'info', 'elastic');
        }

        return $data;
    }

    protected function calculateTotalItemCount() {
        return $this->_count;
    }

    protected function fetchKeys() {
        return array();
    }

    public function getPageCount() {
        $pagination = $this->getPagination();
        return ceil($this->_count/$pagination->pageSize);
    }

    public function getCurrentPage() {
        return Yii::app()->request->getParam($this->getPagination()->pageVar, 0);
    }

    public function createPageUrl($controller, $page) {
        return $controller->createUrl($controller->route, array_merge($_GET, array( ucfirst($controller->id).'_page' => $page)));
    }

    public function getSort($className='ElasticaSort') {
        $this->_sort = parent::getSort('ElasticaSort');
        if($this->_sort===null || !($this->_sort instanceof CSort))
        {

            $this->_sort=new $className;
            if(($id=$this->getId())!='')
                $this->_sort->sortVar=$id.'_sort';
        }
        return $this->_sort;
    }

}