<?php
/**
 * Returns the data from MOngodb in a data provider
 * Extend Yii's CDataProvider
 */
class ElasticaDataProvider extends CDataProvider{

    public $index;
    public $type;


  protected $_elastica_query;
  protected $_totalItemCount;
  protected $_elastica_index;
  protected $_elastica_result_set;
  protected $_sort;
  protected $_count;
  // protected $type;


  public function __construct($index, $query, $config, $type=null) {
    if (!$type) {
      $type = $index;
    }
      $index =   preg_replace('/[^a-z0-9]/','',strtolower(Yii::app()->name));

    $this->type = strtolower($type);
    $this->setId($type);
    $this->_elastica_query = $query;
    $client = Yii::app()->elastica->getClient();
    $this->_elastica_index = $client->getIndex($index);

      $this->type = new Elastica\Type($this->_elastica_index,$type);


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
      $descending = isset($order_array[1]) ? 'desc' : 'asc';
      $this->_elastica_query->setSort(array(
        $name.'.untouched'=>array('order' => $descending, 'mode' => 'max'),

      ));
    }

    $this->_count = $this->_elastica_index->count($this->_elastica_query);
    Yii::log("elastic count :".$this->_count);
    if (($pagination = $this->getPagination()) !== false) {
      $limit = $pagination->pageSize;
      $current_page = Yii::app()->request->getParam($this->getPagination()->pageVar, 0);
      $current_page++;

      $skip = --$current_page * $limit;

      if ($skip >= $this->_count) {
        $skip = 0;
        $this->getPagination()->setCurrentPage(0);
      }

      $this->_elastica_query->setLimit($limit);
      $this->_elastica_query->setFrom($skip);
    } else {
      $this->_elastica_query->setLimit($this->_count);
    }

   //  $this->_elastica_result_set = $this->_elastica_index->search($this->_elastica_query);
    $this->_elastica_result_set = $this->type->search($this->_elastica_query);

    if (($pagination = $this->getPagination()) !== false) {
      $pagination->setItemCount($this->getTotalItemCount());
    }

    $data = array();
    foreach ($this->_elastica_result_set->getResults() as $key => $value) {
     //
      if(is_string($this->type) && class_exists($this->type,false) && method_exists($this->type,'setAttributes')){

          $type = ucfirst($this->type);
          $obj = new $type;
          $obj->setAttributes($value->getSource());
      }else{
          //print_r($value->getSource());
          $obj = (object)($value->getSource());
      }

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
    return ceil($this->calculateTotalItemCount()/$pagination->pageSize);
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
