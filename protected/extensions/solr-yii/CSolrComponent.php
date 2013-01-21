<?php
/**
 * SolrClient class file.
 *
 * @author chahedous
 * @version 2.2
 * @link http://www.innovatunisia.com/
 * @copyright Copyright &copy; 2010 chahedous
 *
 * Copyright (C) 2010 chahedous.
 *
 *     This program is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU Lesser General Public License as published by
 *     the Free Software Foundation, either version 2.1 of the License, or
 *     (at your option) any later version.
 *
 *     This program is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU Lesser General Public License for more details.
 *
 *     You should have received a copy of the GNU Lesser General Public License
 *     along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * For third party licenses and copyrights, please see solr-php-client/LICENSE
 *
 */

/**
 * Include the the Solr php client class.
 */
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'phpSolrClient'.DIRECTORY_SEPARATOR.'Service.php');

class CSolrComponent extends CApplicationComponent{
    
    /**
    * Host name
    * 
    * @var strinf
    */
    public $host='localhost';
    
    /**
    * The port of the solr server
    * 
    * @var int
    */
    public $port='8983';
    
    /**
    * The Solr index (core)
    * 
    * @var string (the url path)
    */
    public $indexPath = '/solr';
    public $_solr;
    
    public function init()
    {  parent::init();
        if(!$this->host || !$this->indexPath)
            throw new CException('No server or index selected.');
        else 
            $this->_solr = new Apache_Solr_Service($this->host, $this->port, $this->indexPath);
        if (!$this->_solr->ping()){
            echo "$this->host, $this->port, $this->indexPath"
            throw new CException('Solr service not responding.');
        }
    }
    
    /**
    * This function add or update one entry on solr index
    * 
    * @param array $document Example: array('id'=>1,'title'=>'the title of the article')
    */
    
    public function updateOne($document=array()){
        if(!is_array($document)){
            throw new CException('Document must be an array.');
        }
        $part = new Apache_Solr_Document();
        foreach ( $document as $key => $value ) {
            $part->$key = $value;
        }
        $documents[] = $part;
        try {
          $this->_solr->addDocuments( $documents );
          $this->_solr->commit();
          $this->_solr->optimize();
          return true;
        }
        catch ( Exception $e ) {
            throw new CException('Solr error: '.$e->getMessage());
        }
        return false;
    }
    /**
    * This function add or update one entry on solr index
    * 
    * @param array $document Example: 
    * array('0'=>array('id'=>1,'title'=>'the title of the article 2'),                   
    *       '1'=>array('id'=>2,'title'=>'the title of the article 2')
    *       );
    */
    
    public function updateMany($documents=array()){
        if(!is_array($documents)){
            throw new CException('Documents must be an array.');
        }
        foreach ( $documents as $item => $document ) {
            $part = new Apache_Solr_Document();
            foreach ( $document as $key => $value ) {
                $part->$key = $value;
            }
            $docs[] = $part;
        }
        
        try {
          $this->_solr->addDocuments( $docs );
          $this->_solr->commit();
          $this->_solr->optimize();
          return true;
        }
        catch ( Exception $e ) {
            throw new CException('Solr error: '.$e->getMessage());
        }
        return false;
    }
    
    /**
    * Return resutls for a query
    * 
    * @param mixed $query
    * @param mixed $offset
    * @param mixed $limit
    * @param mixed $additionalParameters
    * 
    * Example: $additionalParameters = array('facet' => 'true',
    *                               'facet.field' => 'links',
    *                               'sort'=> 'id desc'
    *                                );
    * 
    * @return Apache_Solr_Response 
    * 
    * 
    */
    public function get($query, $offset = 0, $limit = 30, $additionalParameters=array())      {
      $response = $this->_solr->search($query, $offset, $limit, $additionalParameters);
      if ( $response->response->numFound > 0 ) {
            return($response);
      }
    }
}
?>
