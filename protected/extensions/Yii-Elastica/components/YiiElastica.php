<?php
/**
 *
 * Class YiiElastica
 */
class YiiElastica extends CApplicationComponent {
  
  private $_client;

  public $host;
  public $port;
  public $servers;
  public $debug;



  public function getClient() {
    if ($this->_client) {
      return $this->_client;
    }


    if ($this->debug) {
      define('DEBUG',true);
    }

    if ($this->servers) {
      $this->_client = new Elastica\Client(array($this->servers));
    } elseif ($this->host && $this->port) {
      $this->_client = new Elastica\Client(array(
        'host' => $this->host,
        'port' => $this->port
      ));
    } else {
      throw new Exception("Error initiating elastica client", 1);  
    }

    return $this->_client; 
    
  }

}
