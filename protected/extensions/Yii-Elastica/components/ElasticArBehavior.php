<?php
/**
 * Created by PhpStorm.
 * User: yiqing
 * Date: 13-11-22
 * Time: 下午11:34
 * To change this template use File | Settings | File Templates.
 */
Yii::setPathOfAlias('Elastica',Yii::getPathOfAlias('application.vendors.Elastica'));

class ElasticArBehavior  extends CActiveRecordBehavior
{

    /**
     * @return IElasticaSearchable|CComponent the owner component that this behavior is attached to.
     */
    public function getOwner()
    {
        return parent::getOwner();
    }
    /**
     * @var bool whether to automatically index and delete documents in elastic search. Default is true.
     */
    public $elasticAutoIndex = true;

    /**
     * @var string the elastic index name used by this ActiveRecord model
     */
    public $esIndex ='yii_space';

    /**
     * @var
     */
    protected $_elasticConnection;

    /**
     * @var float the document score
     */
    protected $_score;

    /**
     * @return Elastica|IApplicationComponent
     * @throws Exception
     */
    public function getElasticConnection()
    {
        if ($this->_elasticConnection === null) {
            // TODO the component name should be configured
            if (Yii::app()->hasComponent('elastica'))
                $this->_elasticConnection = Yii::app()->getComponent('elastica');
            else
                throw new \Exception(__CLASS__." expects an 'elastica' application component");
        }
        return $this->_elasticConnection;
    }

    /**
     * @return string the index where this record will be stored. Default is the (sanitized)
     * application name. To customize define $elasticIndex or getElasticIndex() in the record.
     */
    public function getElasticIndex()
    {

        if($this->esIndex === null){
            $this->esIndex = preg_replace('/[^a-z0-9]/','',strtolower(Yii::app()->name));
        }
        return $this->esIndex ;
    }

    /**
     * @return string the type under which this record will be stored. Default is the lower case
     * class name of the active record. To customize define $elasticType or getElasticType() in the record.
     */
    public function getElasticType()
    {
        return strtolower(get_class($this->owner));
    }

    /**
     * @return float how much this record is relevant to the search query. Only set on queried records.
     */
    public function getElasticScore()
    {
        return $this->_score;
    }

    /**
     * @param float how much this record is relevant to the search query. Do not set manually.
     */
    public function setElasticScore($score)
    {
        $this->_score = $score;
    }

    /**
     * Add/Update this document to/in the elasticsearch index
     */
    public function indexElasticDocument()
    {
        // $client = new Elastica\Client();
        $client  =  $this->getElasticConnection()->getClient() ;

        $index = $client->getIndex($this->getElasticIndex());
        $type = $index->getType($this->getElasticType());
        /*
        $id = 2;
        $data = array('firstname' => 'Nicolas', 'lastname' => 'Ruflin');
        $doc = new \Elastica\Document($id, $data);
        */

        $type->addDocument($this->createElasticDocument());


        // $this->getElasticConnection()->index($this->createElasticDocument());
    }

    /**
     * @param \Elastica\Document $document
     * $document the document where the indexable data must be applied to.
     * Override this method in a record to define which data should get indexed. By default all
     * record attributes get indexed.
     */
    public function populateElasticDocument(\Elastica\Document $document)
    {
        $document->setId($this->owner->getPrimaryKey());
        foreach($this->owner->attributeNames() as $name)
            $document->{$name} = $this->owner->{$name};
    }


    /**
     * @param \Elastica\Result $document
     *  $document the document that is providing the data for this record.
     * Override this method to apply custom data from a search result to a new record.
     */
    public function parseElasticDocument(\Elastica\Result $document)
    {
        $this->owner->setPrimaryKey($document->getId());
        /*
        if ($document instanceof SearchResult)
            $this->owner->setElasticScore($document->getScore());
        */
        foreach($document->getData() as $attribute => $value)
            $this->owner->{$attribute} = $value;
    }

    /**
     * @return Document a new elasticsearch document instance
     */
    protected function createElasticDocument()
    {
        /*
        $document = new Document();
        $document->setConnection($this->_elasticConnection);
        $document->setIndex($this->_elasticConnection->indexPrefix.$this->owner->elasticIndex);
        $document->setType($this->owner->elasticType);
        $this->owner->populateElasticDocument($document);
          */
        $document = new \Elastica\Document();
        $document->setIndex($this->getElasticIndex());
        $document->setType($this->getElasticType());
        $this->getOwner()->populateElasticDocument($document);

        return $document;
    }

    /**
     * Invoked after the model is saved, adds the model to elastic search
     */
    public function afterSave($event)
    {
        if ($this->elasticAutoIndex)
            $this->indexElasticDocument();
        parent::afterSave($event);
    }

    /**
     * Invoked after the model is deleted, removes the model from elastic search too.
     */
    public function afterDelete($event)
    {
        if ($this->elasticAutoIndex){
            // $client = new \Elastica\Client();
            $client = $this->getElasticConnection()->getClient();
            $index = $client->getIndex($this->getElasticIndex());
            $type = $index->getType($this->getElasticType());
             $type->deleteDocument($this->createElasticDocument());
        }

        parent::afterDelete($event);
    }
}
