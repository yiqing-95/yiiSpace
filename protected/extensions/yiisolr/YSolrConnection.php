<?php
/**
 * YSolrConnection class file.
 *
 * @author Clevertech
 * @version 1.0
 * @link http://www.clevertech.biz/
 * @copyright Copyright &copy; 2011 clevertech
 *
 * Copyright (C) 2011 clevertech.
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
 */

class YSolrConnection extends CApplicationComponent
{
    
    /**
     * @var string the hostname on which SOLR is running
     */
    public $host='localhost';
    
    /**
    * @var int sever port for SOLR
    */
    public $port='8983';

    /**
    * @var int sever port for secure SOLR connections
    */
    public $securePort='8443';

    /**
	 * @var boolean whether the connection should be use the secure port for a secure connection
	 */
	public $secureConnect=false;
	
	/**
	 * @var string HTTP Basic Authentication Username 
	 */
	public $username='';
	
	/** 
	 * @var string HTTP Basic Authentication Password 
	 */
	public $password='';
    
    /**
    * @var string the url path for the index against which you want to execute commands
    */
    public $indexPath = '/solr';
    

    private $_solrClient;
    
    
    public function init()
    {  
		parent::init();
        
		if (!extension_loaded('solr')) 
		{
			throw new CException(Yii::t('yiisolr','The YiiSolr extension requires the PHP Solr extension to be loaded. Please see http://php.net/manual/en/book.solr.php for more information on this extension.'));
		}
		
		$options = array
		(
		    'hostname' => $this->host,
		    'login'    => $this->username,
		    'password' => $this->password,
		    'port'     => ($this->secureConnect) ? $this->securePort : $this->port,
		    'path'     => $this->indexPath,
		);

		$this->_solrClient = new SolrClient($options);
	}
    
    /**
    * Method to add a single document to the index
    * @param array $document Example: array('id'=>1,'title'=>'the title of the article')
    * @return boolean whether or not the update worked
    * @throws CException if there was an error upon attempting to update the index
    */
    public function updateOne($document=array())
    {
        if(!is_array($document))
		{
            throw new CException(Yii::t('yiisolr', 'YSolrConnection::updateOne() - Input document must be an array.'));
        }

        $doc = new SolrInputDocument();

		foreach($document as $key=>$value) 
		{
            $doc->addField($key, $value);
        }

        return $this->handleUpdateResponse($this->_solrClient->addDocument($doc));

	}
    
    /**
    * Method to add multiple documents at once to the index
    * @param array or arrays, each inner array represents a single $document Example: 
	* <pre>
	* array('0'=>array('id'=>1,'title'=>'the title of the article 1'),                   
    *       '1'=>array('id'=>2,'title'=>'the title of the article 2')
    *       );
	* </pre>
	* @return boolean whether or not the update worked
	* @throws CException if there was an error upon attempting to update the index
    */
    public function updateMany($documents=array())
    {
        if(!is_array($documents))
		{
            throw new CException(Yii::t('yiisolr', 'YSolrConnection::updateMany() - Input document must be an array.'));
        }

        foreach($documents as $item => $document) 
        {
            $doc = new SolrInputDocument();
            foreach($document as $key=>$value) 
			{
	            $doc->addField($key, $value);
	        }
            $docs[] = $doc;
        }

		return $this->handleUpdateResponse($this->_solrClient->addDocuments($docs));
	}
    
    /**
    * Executes A Solr Query
    * 
    * @param string $query
    * @param int $offset
    * @param int $limit
    * @param array $additionalParameters
    * 
    * Example: $additionalParameters = array('facet' => 'true',
    *                               'facet.field' => 'links',
    *                               'sort'=> 'id desc'
    *                                );
    * 
    * @return YSolrSearchResponse 
    * 
    * 
    */
    public function get($query, $offset=0, $limit=30, $additonalParameters=array())
    {
		//TODO: DEAL WITH ADDITIONPARAMETERS ARRAY TO PROPERLY SET THESE ADDITIONAL PARAMETERS ON THE PHP EXT API
		
		//Yii::import('common.components.yiisolr.YSolrSearchResponse');
        require_once(dirname(__FILE__). DIRECTORY_SEPARATOR .'YSolrSearchResponse.php');

		$solrQuery = new SolrQuery();

		$solrQuery->setQuery($query);

		$solrQuery->setStart($offset);

		$solrQuery->setRows($limit);

		$queryResponse = $this->_solrClient->query($solrQuery);
		
		return new YSolrSearchResponse($queryResponse);
    }

    /**
     * handles the solr update response
     * @param SolrUpdateResponse class 
     */
    protected function handleUpdateResponse($response)
	{
		if($response->success())
		{
			$this->_solrClient->commit();
			$this->_solrClient->optimize();
			return true;
		}
		else
		{
			$errorMsg = Yii::t('yiisolr','There was an error when attempting to add a document. Status: {status} - Message: {msg}.',
				array('{status}'=>$this->_solrClient->http_status, '{msg}'=>$this->_solrClient->http_status_message));
			throw new CException($errorMsg);
		}
		
		return false;
	}
}
?>