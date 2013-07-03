<?php
/**
 * Zabbix PHP API (via the JSON-RPC Zabbix API)
 * @version 1.0 Public Release - December 23, 2009
 * @author Andrew Farley @ http://andrewfarley.com
 * @see http://andrewfarley.com/zabbix_php_api
 *
 * Based on the Zabbix 1.8 API - The official docs are still slim...
 * @see http://www.zabbix.com/documentation/1.8/api
 *
 * @requires PHP 5.2 or greater
 * @requires PHP JSON functions (json_encode/json_decode)
 * @requires PHP CURL
 * @requires Zabbix to be 1.7.2 or greater (so it has the API), preferably 1.8
 *
 * @copyright 2009 Andrew Farley - http://andrewfarley.com
 * @license Wishlist-ware
 * --------------------------------------------------------------------------------
 * Definition of "Wishlist-ware"
 *
 * Andrew Farley (andrewfarley.com) wrote this file. As long as you retain the 
 * copyright and license you can do whatever you want with this. If you use this 
 * and it helps benefit you or your company (and you can afford it) buy me an item
 * from one of my wish lists (on my website) or if we cross paths buy me caffeine
 * in some form and we'll call it even!
 * --------------------------------------------------------------------------------
 *
 * Design Notes:
 *      This was designed using a static design structure, where you call all
 *      methods of this class statically, and it returns data from the Zabbix server
 *      directly to you.  This was so that this class could remain abstracted and not
 *      require instantiation all over the place on large and fractured codebases.
 *
 *      Note: None of the actual objects/methods were implemented in this class, this
 *      leaves this class open to be able to use future methods and objects when they
 *      implement them.  It also makes it easier to make a mistake in calling this
 *      class though as it does no validation of your input, checking for a valid
 *      method, or parameters before calling the remote API.  A future version of the
 *      API will (possibly) create all the classes necessary to help validate data
 *      and ease the use of this API.
 *
 * Simple Usage Examples: 
 *      This is necessary before doing anything.  Your user must have API privileges
 *          ZabbixAPI::login('http://mywebsite.com/path/to/zabbix', 'api_user', 'api_pass');
 *      With no parameters, this simply grabs all valid userid into an array
 *          $users = ZabbixAPI::fetch_column('user','get');
 *      If you want verbose output from any fetch request, add parameter (extendoutput = 1)
 *          $all_hosts_verbose = ZabbixAPI::fetch_array('host','get',array('extendoutput'->1));
 *      This is how you update a user's properties, you just need the userid, and the
 *      value you want to set (in this case, refresh)
 *          $result = ZabbixAPI::query('user','update',array('userid'=>1, 'refresh'=>1000));
 *
 *      NOTE: If any methods return PHP === FALSE, then you can use 
 *      ZabbixAPI::getLastError() to check what the problem was!  :)
 */
class ZabbixAPI {
    /**
     * Private constants, not intended to be changed or edited
     */
    CONST ZABBIX_API_URL = 'api_jsonrpc.php';
    CONST PHPAPI_VERSION = '1.0';

    // The private instance of this class
    private static $instance;
    
    /**
     * Private class variables
     */
    protected $url              = '';
    protected $username         = '';
    protected $password         = '';
    protected $auth_hash        = NULL;
    protected $debug            = false;
    protected $last_error       = false;
    
    // we don't permit an explicit call of the constructor! ($api = new ZabbixAPI())
    protected function __construct() { }
    // we don't permit cloning of this static class ($x = clone $v)
    protected function __clone() { }
    
    /**
     * Public facing functions
     */
     
     /**
      * Login, this will attempt to login to Zabbix with the specified username and password
      */
    public static function login($url, $username, $password) {
        // Initialize instance if it isn't already
        self::__init();
        // Set properties
        // Add ending / if it's not there
        if (substr($url,strlen($url)-1,1) != '/')
            $url .= '/';
        self::$instance->url        = $url;
        self::$instance->username   = $username;
        self::$instance->password   = $password;
        // Attempt to login
        return self::$instance->__login();
    }
    
    public static function debugEnabled($value) {
        // Initialize instance if it isn't already
        self::__init();
        if ($value === TRUE)
            self::$instance->debug = true;
        else
            self::$instance->debug = false;
    }
    
    /**
     * Generic API Call function, with no method or property validation
     */
    public static function fetch($object, $method, $properties = array()) {
        return self::$instance->__callAPI($object.'.'.$method, $properties);
    }
    
    /**
     * Alias to fetch, but simply returns TRUE or FALSE.  This is typically for doing updates
     * and other "set/update" type commands
     */
    public static function query($object, $method, $properties = array()) {
        return ( self::fetch($object, $method, $properties) == FALSE ? FALSE : TRUE );
    }

    /**
     * Force return value to be an array
     */
    public static function fetch_array($object, $method, $properties = array()) {
        $return = self::$instance->fetch($object, $method, $properties);
        if (is_array($return))
            return $return;
        else
            return array($return);
    }
    
    /**
     * Get the last error that
     */
    public static function getLastError() {
        return self::$instance->last_error;
    }
    
    /**
     * Force return value to be a string
     */
    public static function fetch_string($object, $method, $properties = array()) {
        $return = self::$instance->fetch($object, $method, $properties);
        if (is_array($return))
            return self::__return_first_string($return);
        else
            return $return;
    }

    /**
     * Force return value to be the first element of an array (first "row" or record)
     */
    public static function fetch_row($object, $method, $properties = array()) {
        $return = self::$instance->fetch($object, $method, $properties);
        if (is_array($return))
            foreach ($return as $item)
                return $item;
        else
            return $return;
    }
    
    /**
     * Force return value to be the first column of the first row of an array, in the form of an array
     */
    public static function fetch_column($object, $method, $properties = array()) {
        $return = self::$instance->fetch($object, $method, $properties);
        if (!is_array($return))
            return array($return);
        else {
            $output = array();
            foreach ($return as $item) {
                $output[] = array_shift($item);
            }
            return $output;
        }
    }
    
    /**
     * Private/protected functions, not to be called by any code outside this class
     */
     
    /**
     * Private init function, which is called to ensure our instance is initialized
     */
    private static function __init() {
        if (get_class(self::$instance) != "ZabbixAPI")
            self::$instance = new ZabbixAPI();
    }
    
    /**
     * Recursive function to get the first non array element of a multidimensional array
     */
    private static function __return_first_string($array) {
        foreach($array as $item) {
            if (is_array($item))
                return self::__return_first_string($item);
            else
                return $item;
        }
    }
    
    /**
     * Builds a JSON-RPC request, designed just for Zabbix
     */
    private static function __buildJSONRequest($method, $params = array()) {
        // This is our default JSON array
        $request = array(
            'auth' => self::$instance->auth_hash,
            'method' => $method,
            'id' => 1,  // NOTE: this needs to be fixed I think?
            'params' => ( is_array($params) ? $params : array() ),
            'jsonrpc' => "2.0"
        );
        // Return our request, in JSON format
        return json_encode($request);
    }
    
    /**
     * The private function that performs the call to a remote RPC/API call
     */
    private function __callAPI($method, $params = array()) {
        // Initialize instance if it isn't already, so no fatal PHP errors
        self::__init();
        
        // Reset our "last error" variable
        self::$instance->last_error = false;
        
        // Make sure we're logged in, or trying to login...
        if ($this->auth_hash == NULL && $method != 'user.authenticate')
            return false;  // If we're not logged in, no wasting our time here
        
        // Try to retrieve this...
        $data = self::__jsonRequest( 
            $this->url.self::ZABBIX_API_URL, 
            self::__buildJSONRequest( $method, $params )
        );
        
        if ($this->debug)
            echo "Got response from API: ($data)\n";
        
        // Convert return data (JSON) to PHP array
        $decoded_data = json_decode($data, true);
        
        if ($this->debug)
            echo "Response decoded: (".print_r($decoded_data,true)."\n";
        
        // Return the data if it's valid
        if ( isset($decoded_data['id']) && $decoded_data['id'] == 1 && !empty($decoded_data['result']) ) {
            return $decoded_data['result'];
        } else {
            // If we had a actual error, put it in our instance to be able to be retrieved/queried
            if (!empty($decoded_data['error']))
                self::$instance->last_error = $decoded_data['error'];
            return false;
        }
    }
    
    /**
     * Private login function to perform the login
     */
    private function __login() {
        // Try to login to our API
        $data = $this->__callAPI('user.authenticate', array( 'password' => $this->password, 'user' => $this->username ));
        
        if ($this->debug)
            echo "__login() Got response from API: ($data)\n";
        
        if (isset($data) && strlen($data) == 32) {
            $this->auth_hash = $data;
            return true;
        } else {
            $this->auth_hash = NULL;
            return false;
        }
    }
    
    /**
     * Note: Headers must be in the string form, in an array...
     *   eg. $headers  =  array('Content-Type: application/json-rpc', 'Another-Header: value goes here');
     */
    private static function __jsonRequest($url, $data = '', $headers = array()){
        $c = curl_init($url);
        // These are required for submitting JSON-RPC requests
        $headers[]  = 'Content-Type: application/json-rpc';
        // Well, ok this one isn't, but it's good to conform (sometimes)
        $headers[]  = 'User-Agent: ZabbixAPI v'.ZabbixAPI::PHPAPI_VERSION.' - http://andrewfarley.com/zabbix_php_api';
    
        $opts = array(
                CURLOPT_RETURNTRANSFER => true,     // Allows for the return of a curl handle
                //CURLOPT_VERBOSE => true,          // outputs verbose curl information (like --verbose with curl on the cli)
                //CURLOPT_HEADER => true,           // In a verbose output, outputs headers
                CURLOPT_TIMEOUT => 30,              // Maximum number of seconds to allow curl to process the entire request
                CURLOPT_CONNECTTIMEOUT => 5,        // Maximm number of seconds to establish a connection, shouldn't take 5 seconds
                CURLOPT_SSL_VERIFYHOST => false,    // Incase we have a fake SSL Cert...
                CURLOPT_SSL_VERIFYPEER =>false,     //    Ditto
                CURLOPT_FOLLOWLOCATION => true,     // Incase there's a redirect in place (moved zabbix url), follow it automatically
                CURLOPT_FRESH_CONNECT => true       // Ensures we don't use a cached connection or response
                 );
    
        // If we have headers set, put headers into our curl options
        if(is_array($headers) && count($headers)){
            $opts[CURLOPT_HTTPHEADER] = $headers;
        }
        
        // This is a POST, not GET request
        $opts[CURLOPT_CUSTOMREQUEST] = "POST";
        $opts[CURLOPT_POSTFIELDS] = ( is_array($data) ? http_build_query($data) : $data );
        
        // This is useful, incase we're remotely attempting to consume Zabbix's API to compress our data, save some bandwidth
        $opts[CURLOPT_ENCODING] = 'gzip';
        
        // If we're in debug mode
        if (self::$instance->debug) {
            echo "CURL URL: $url\n<br>";
            echo "CURL Options: ".print_r($opts, true);
        }

        // Go go gadget!  Do your magic!
        curl_setopt_array($c, $opts);
        $ret = @curl_exec($c);
        curl_close($c);
        return $ret;
    }
}