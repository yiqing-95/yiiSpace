Introduction
============

YiiSolr is a very simple wrapper to the [PHP Apache Solr Extension](http://php.net/manual/en/book.solr.php) that allows you to communicate effectively with the Apache Solr server in PHP 5 in your Yii Applications. 

This extension has also been written to conform to the interface defined by another Yii Solr extension, [Solr](http://www.yiiframework.com/extension/solr), which uses the [solr-php-client](http://code.google.com/p/solr-php-client/) library to connect with Solr. So, this extension gives you another choice in client libraries with which to connect to Solr. And you can write the same code against either extension so changing your Solr connection implementation should is easy.


Initial Installation
====================
You can either download the source files or clone from:

git://github.com/clevertech/yiisolr.git

Then just add them to the extensions folder under your Yii application.

Configuration
=============
The class YSolrConnection is defined as a Yii application component, so can be configured as such as part of your application configuration in the 'components' definitions:

	'solrManager'=>array(
		'class'=>'ext.yiisolr.YSolrConnection',
		'host' => 'localhost',
		'port' => 8983,
		'username' => '',
		'password' => '',
		'indexPath' = '/solr',
	),
	
See the public properties of the YSolrConnection class for more settings that can be defined in this configuration.

Example Usage:
==============

	//Adding one document to your index
	Yii::app()->solrManager->updateOne(array('id'=>1,'title'=>'Test Title One'));
	
	
	//Adding many documents to your index at once
	$data = array(
		array('id'=>1,'title'=>'Test Title One'),
		array('id'=>2,'title'=>'Test Title Two'),
		array('id'=>3,'title'=>'Test Title Three')
	);
	Yii::app()->solrManager->updateMany($data));
	
	
	//To search for these added documents
	$result = Yii::app()->solrManager->get('title:Test', 0, 20);
	//get the number of returned results
	echo "Number of results returned: ".$result->response->numFound;
	//iterate over the returned docs array to get information from each document
	foreach($result->response->docs as $doc)
	{
	   echo "{$doc->title} <br>";
	}
	

Components
==========

This extension as two main classes:

YSolrConnection class
---------------------

Provides an easy to use interface to making Solr queries using the PHP Apache Solr extension. 

YSolrSearchResponse class
-------------------------

Encapsulates the response data and provides and easy to use interface to retrieve the documents.


Resources
=========

[PHP Apache Solr Extension](http://php.net/manual/en/book.solr.php)