<?php
/**
 * php async call with gearman support
 */
class PHPAsyncClient {

    protected static
        $_gearman_config = array(
            'host' => '127.0.0.1',
            'port' => 4730,
        ),
        $_instance = null;

    protected
        $_client;

    /**
     * @param null $options gearman connection config
     */
    public function __construct($options = null) {
        if (!empty($options) && !empty($options['host']) && !empty($options['port'])) {
            self::$_gearman_config = array_merge(self::$_gearman_config, $options);
        }
        spl_autoload_register(array('PHPAsyncClient', 'autoLoad'));
        $this->_client = new GearmanPHP_GearmanClient();
        $this->_client->addServer(self::$_gearman_config['host'], self::$_gearman_config['port']);
    }

    static public function autoLoad($className) {
        $path = str_replace('_', DIRECTORY_SEPARATOR, $className);
        $classFile = dirname(__FILE__).'/lib/'.$path.'.php';
        if (file_exists($classFile)) {
            require_once $classFile;
        }
    }

    /**
     * @static check if in cli runtime
     * @param string $file main file
     * @return bool
     */
    static public function is_main($file = __FILE__) {
        return (php_sapi_name() == 'cli' && !empty($_SERVER['argv']) && $_SERVER['argv'][0] == $file);
    }

    /**
     * @static check if in AsyncCallback
     * @param string $file main file
     * @return bool
     */
    static public function in_callback($file = __FILE__) {
        if (!self::is_main($file) || count($_SERVER['argv']) <= 1)
            return false;
        $args_src = $_SERVER['argv'][1];
        parse_str($args_src, $args);
        return isset($args['class']) && isset($args['method']);
    }

    /**
     * @static call in worker process
     *
     */
    static public function parse() {
        $args_src = $_SERVER['argv'][1];
        parse_str($args_src, $args);
        if (isset($args['class']) && isset($args['method'])) {
            $params = isset($args['params']) ? $args['params'] : null;
            $params = json_decode($params, true);
            if (!empty($params) && !is_array($params)) {
                $params = array($params);
            }
            call_user_func_array(array($args['class'], $args['method']), array($params));
        }
    }

    public function AsyncCall($className, $methodName, array $params = null, array $callback = null, $callback_file = __FILE__) {
        $args = array(
            'class' => $className,
            'method' => $methodName,
            'params' => $params,
            'file' => $callback_file,
        );
        if (!empty($callback) && isset($callback['class']) && isset($callback['method'])) {
            if (!isset($callback['params'])) {
                $callback['params'] = null;
            }
            $args['callback'] = array(
                'class' => $callback['class'],
                'method' => $callback['method'],
                'params' => $callback['params'],
            );
        }
        $this->_client->addTaskBackground('php_asynccall', $args);
        $this->_client->runTasks();
    }

    /**
     * @static
     * @return PHPAsyncClient
     */
    static public function getInstance() {
        if (empty(self::$_instance)) {
            self::$_instance = new PHPAsyncClient();
        }
        return self::$_instance;
    }
}