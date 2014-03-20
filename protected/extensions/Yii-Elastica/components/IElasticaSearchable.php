<?php
/**
 * Created by PhpStorm.
 * User: yiqing
 * Date: 13-11-23
 * Time: 上午1:47
 * To change this template use File | Settings | File Templates.
 */
/**
 * these methods are also  available in the ElasticArBehavior , so they are all overWritable !
 * if your Ar implements this interface ,thus the underline behavior will be ignored !
 * Interface IElasticaSearchable
 */
interface IElasticaSearchable {

    /**
     * @return string
     */
    public function getElasticIndex();

    /**
     * @return string
     */
    public function getElasticType();

    /**
     *  this method is optional it will overwrite the version which the ElasticaArBehavior implemented
     *
     *  $document the document where the indexable data must be applied to.
     *
     *   {
            $document->setId($this->id);
            $document->name     = $this->name;
            $document->street   = $this->street;
            }
     * @param \Elastica\Document $document
     * @return mixed
     *
     *
     */
     public function populateElasticDocument(\Elastica\Document $document);


    /**
     * $document the document that is providing the data for this record.
     *    {
            // You should always set the match score from the result document
            if ($document instanceof SearchResult)
            $this->setElasticScore($document->getScore());

            $this->id       = $document->getId();
            $this->name     = $document->name;
            $this->street   = $document->stree;
            }
     * @param \Elastica\Document $document
     * @return mixed
     */
    public function parseElasticDocument(\Elastica\Document $document);

} 