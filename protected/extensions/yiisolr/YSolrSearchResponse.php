<?php
/**
 * YSolrSearchResponse class file.
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

/**
 * Represents a Solr Search Response Object.  Just wraps the PHP Solr Extension response object to provide a compatible interface
 * for those using Apache_Solr_Response object and/or the Yii Solr Extension (http://www.yiiframework.com/extension/solr)
 */
class YSolrSearchResponse extends CComponent
{
			
	public $response;
			
	private $_solrResponse;

	/**
	 * Constructor. Takes in the SolrQueryResponse object returned from a SolrClient->query() call
	 * @param SolrQueryResponse object $rawResponse
	 */
	public function __construct($solrQueryResponse)
	{
		
		if(! $solrQueryResponse instanceof SolrQueryResponse)
		{
			throw new CException(Yii::t('yiisolr', 'YSolrSearchResponse::construct - Input must be of type SolrQueryResponse.'));
		}
		
		$this->_solrResponse = $solrQueryResponse;
		$this->response = new YSolrQueryResponseObject($solrQueryResponse->getResponse()->response);	
		
	}

	/**
	 * Get the HTTP status code
	 * @return integer
	 */
	public function getHttpStatus()
	{
		return $this->_solrResponse->getHttpStatus();
	}

	/**
	 * Get the HTTP status message of the response
	 * @return string
	 */
	public function getHttpStatusMessage()
	{
	    return $this->_solrResponse->getHttpStatusMessage();
	}

	/**
	 * Get content type of this Solr response
	 * @return string
	 */
	public function getType()
	{
		//TODO: Parse the raw response header info for content type
		//EXAMPLE OF RETURNED DATA FROM $this->_solrResponse->getRawResponseHeaders()
		/*
		"HTTP/1.1 200 OK
		Content-Type: text/xml; charset=utf-8
		Content-Length: 764
		Server: Jetty(6.1.3)

		"
		*/
		
		return 'text/xml';
	}

	/**
	 * Get character encoding of this response. Should usually be utf-8, but just in case
	 *
	 * @return string
	 */
	public function getEncoding()
	{
		//TODO: Parse the raw response header info for content type
		//EXAMPLE OF RETURNED DATA FROM $this->_solrResponse->getRawResponseHeaders()
		/*
		"HTTP/1.1 200 OK
		Content-Type: text/xml; charset=utf-8
		Content-Length: 764
		Server: Jetty(6.1.3)

		"
		*/
		return 'utf-8';
	}

	/**
	 * Get the raw response as it was given to this object
	 *
	 * @return string
	 */
	public function getRawResponse()
	{
		return $this->_solrResponse->getRawResponse();
	}

}

class YSolrQueryResponseObject
{
	private $_response;
	
	public $docs = array();
	
	/**
	 * Constructor. Takes in the high level SolrObject object type returned from a SolrClient->query() call
	 * @param SolrQueryResponse object $rawResponse
	 */
	public function __construct($solrObject)
	{
		
		if(! $solrObject instanceof SolrObject)
		{
			throw new CException(Yii::t('yiisolr', 'YSolrQueryResponseObject::construct - Input must be of type SolrObject.'));
		}
		
		$this->_response = $solrObject;	
		
		foreach($this->_response->docs as $document)
		{
		   $this->docs[] = new YSolrObject($document);
		}
	}
	
	/**
	 * Magic get method to return properties of the underlying PHP SolrObject instance
	 */
	public function __get($key)
	{
		if (isset($this->_response->$key))
		{
			return $this->_response->$key;
		}

		return null;
	}
}

/**
 * Class to wrap the PHP extension SolrObject in order to expose direct access to the values contained in single element arrays
 * i.e. to support the interface $solrObject->name such that it returns the value of a single element array, rather than the array itself
 */
class YSolrObject
{
	private $_solrObject;
	
	/**
	 * Constructor. Takes in the SolrObject object type returned from a SolrClient->query() call
	 * @param SolrQueryResponse object $rawResponse
	 */
	public function __construct($solrObject)
	{
		
		if(! $solrObject instanceof SolrObject)
		{
			throw new CException(Yii::t('yiisolr', 'YSolrObject::construct - Input must be of type SolrObject.'));
		}
		
		$this->_solrObject = $solrObject;
	}
	
	/**
	 * Magic get method to return properties of the underlying PHP SolrObject instance
	 */
	public function __get($key)
	{
		if (isset($this->_solrObject->$key))
		{
			$value = $this->_solrObject->$key;
			
			if(is_array($value) && count($value) <= 1)
			{
				$value = $value[0];
			}
			return $value;
		}

		return null;
	}
	
}