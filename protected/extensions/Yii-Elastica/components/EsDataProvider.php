<?php
/**
 * @see https://github.com/FriendsOfSymfony/FOSElasticaBundle
 * @see https://github.com/codemix/YiiElasticSearch
 *
 * Data provider that can retrieve results from elastic search.
 *
 * <pre>
 *  $dataProvider = new EsDataProvider(MySearchableModel::model());
 *  $search = $dataProvider->getSearch()->setQuery($_GET['q']);
 *  CVarDumper::dump($dataProvider->getData());
 * </pre>
 *
 * @package YiiElasticSearch
 */
class EsDataProvider extends CDataProvider
{
    /**
     * @var CActiveRecord a model implementing the searchable interface
     */
    public $model;

    /**
     * @var string|null optional name of model attribute to use a attribute key instead of the primary key
     */
    public $keyAttribute;

    /**
     * @var Elastica\Search the search parameters
     */
    protected $_search;

    /**
     * @var Elastica\ResultSet the search result set
     */
    protected $resultSet;

    /**
     * @var mixed the fetched data
     */
    protected $fetchedData;

    /**
     * Initialize the data provider
     * @param CActiveRecord $model the search model
     * @param array $config the data provider configuration
     */
    public function __construct($model, $config = array())
    {
        if (is_string($model))
            $model = new $model;
        $this->model = $model;
        foreach($config as $attribute => $value)
            $this->{$attribute} = $value;
    }


    /**
     * @param Elastica\Search|array $search a Search object or an array with search parameters
     * if you do not config this param , a new one will created based on the searchable model instance .
     */
    public function setSearch($search)
    {
        if (is_array($search)) {
            /*
            $search = new Search(
                $this->model->elasticIndex,
                $this->model->elasticType,
                $search
            );
            */
            // TODO make the client also configurable ！
            $search  = new Elastica\Search(Yii::app()->elastica->getClient());
            $search->addIndex( $this->model->elasticIndex);
            $search->addType( $this->model->elasticType);
           
        }
        if(count($search->getIndices())== 0) {
            $search->addIndex($this->model->elasticIndex);
           // echo 'add Index',$this->model->elasticIndex;
        }
        if( count($search->getTypes())== 0) {
            $search->addType($this->model->elasticType);
        }
        $this->_search = $search;
    }

    /**
     * @return  Elastica\Search
     */
    public function getSearch()
    {
        if ($this->_search === null) {
           /*
            $this->_search = new Search(
                $this->model->elasticIndex,
                $this->model->elasticType,
                array(
                    'query' => array(
                        'match_all' => array()
                    )
                )
            );
           */
            // TODO make the es  client configurable！
            $this->setSearch(new Elastica\Search(Yii::app()->elastica->getClient()));

        }
        return $this->_search;
    }

    /**
     * @return array the facets
     */
    public function getFacets()
    {
        if($this->resultSet===null) {
            $this->fetchData();
        }
        return $this->resultSet->getFacets();
    }

    /**
     * @return array list of data items
     */
    protected function fetchData()
    {
        if($this->fetchedData===null) {
            $search = $this->getSearch();
            if (($pagination = $this->getPagination()) !== false) {
                $pagination->validateCurrentPage = false;
                /*
                $search->addOption('from',$pagination->getOffset());
                $search->addOption('size',$pagination->pageSize);
                */
               //die(__METHOD__ . "|" .( $pagination->getOffset()));
                $type = $search->getQuery()->setFrom($pagination->getOffset());
                $type->setSize($pagination->pageSize);
            }


           //  $this->resultSet = $this->model->getElasticConnection()->search($search);
            $this->resultSet = $this->_search->search();

            $this->fetchedData = array();
            $modelClass = get_class($this->model);
            foreach($this->resultSet->getResults() as $result) {
                $model = new $modelClass;
                $model->setIsNewRecord(false);
                $model->parseElasticDocument($result);
                $this->fetchedData[] = $model;
            }

            if($pagination!==false)
            {
                $pagination->setItemCount($this->getTotalItemCount());
            }
        }
        return $this->fetchedData;
    }

    /**
     * @return array list of data item keys.
     */
    protected function fetchKeys()
    {
        $keys=array();
        foreach($this->getData() as $i=>$data)
        {
            $key=$this->keyAttribute===null ? $data->getPrimaryKey() : $data->{$this->keyAttribute};
            $keys[$i]=is_array($key) ? implode(',',$key) : $key;
        }
        return $keys;
    }

    /**
     * @return integer the total number of data items.
     */
    protected function calculateTotalItemCount()
    {
        if($this->resultSet===null) {
            $this->fetchData();
        }
        return   $this->resultSet->getTotalHits();
    }
}
