<?php
/**
 * GearmanPHP is an attempt to provide a pure php implementation
 * of the gearman php extension (http://php.net/gearman).
 *
 * NOTE:
 * Parts of this library were originally derived in 2009 from the
 * PEAR library Net_Gearman(http://pear.php.net/package/Net_Gearman).
 *
 * Copyright (C) 2009-2010 Christoph Rahles <christoph@rahles.de>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * See LICENSE file for more information.
 *
 * @copyright     2009-2010 Christoph Rahles <christoph@rahles.de>
 * @link          http://wiki.github.com/crahles/GearmanPHP/
 * @package       GearmanPHP
 * @license       GNU LGPL (http://www.gnu.org/copyleft/lesser.html)
 */
require_once 'Base/Common.php';
require_once 'GearmanTask.php';
/**
 * Represents a class for connecting to a Gearman job server and making
 * requests to perform some function on provided data. The function performed
 * must be one registerd by a Gearman worker and the data passed is opaque
 * to the job server.
 *
 * @package       GearmanPHP
 */
class GearmanPHP_GearmanClient
{

    /**
     * Our randomly selected connection
     *
     * @var resource $conn An open socket to Gearman
     */
    protected $_sockets = array();

    /**
     * A list of Gearman servers
     *
     * @var array $servers A list of potential Gearman servers
     */
    protected $_servers = array();

    /**
     * The timeout for Gearman connections
     *
     * @var integer $timeout
     */
    protected $_timeout = 1000;

    protected $_options = array();

    protected $_tasks = array();

    protected $_handles = array();


    protected $_lastReturnCode = null;
    protected $_numerator = 1;
    protected $_denominator = 1;
    protected $_jobHandle = null;
    /**
     * Callbacks registered for each state
     *
     * @var array $callback
     * @see Net_Gearman_Task::attachCallback()
     * @see Net_Gearman_Task::complete()
     * @see Net_Gearman_Task::status()
     * @see Net_Gearman_Task::fail()
     */
    protected $_callbacks = array(
        self::TASK_CREATED   => array(),
        self::TASK_STATUS    => array(),
        self::TASK_WARNING   => array(),
        self::TASK_EXCEPTION => array(),
        self::TASK_FAIL      => array(),
        self::TASK_COMPLETE  => array(),
        self::TASK_WORKLOAD  => array(),
        self::TASK_DATA      => array()
    );

    const TASK_CREATED   = 1;
    const TASK_STATUS    = 2;
    const TASK_WARNING   = 3;
    const TASK_EXCEPTION = 4;
    const TASK_FAIL      = 5;
    const TASK_COMPLETE  = 6;
    const TASK_WORKLOAD  = 7;
    const TASK_DATA      = 8;

    protected $_errorText = null;
    protected $_errorNumber = null;

    /**
     * Get a connection to a Gearman server
     *
     * @return resource A connection to a Gearman server
     */
    protected function _openConnection()
    {
        $socket = null;
        while(!GearmanPHP_Base_Common::isConnected($socket)) {
            $key = array_rand($this->_servers);
            $server = $this->_servers[$key]['host'] . ':' . $this->_servers[$key]['port'];
            $socket = GearmanPHP_Base_Common::connect($server, $this->_timeout);
            if (!GearmanPHP_Base_Common::isConnected($socket)) {
                GearmanPHP_Base_Common::addErrorCallback(array($this, 'errorCallback'));
                unset($this->_servers[$key]);
            }
        }
        return $socket;
    }

    /**
     * Disconnect from Gearman
     *
     * @return      void
     */
    protected function _closeConnection(&$socket)
    {
        GearmanPHP_Base_Common::close($socket);
    }

    /**
     * Creates a GearmanClient instance representing a client that connects
     * to the job server and submits tasks to complete.
     *
     * @return GearmanPHP_GearmanClient
     */
    public function  __construct()
    {
    }

    /**
     * Create a copy of a GearmanClient object
     *
     * @return bool|GearmanPHP_GearmanClient
     */
    public function __clone()
    {
        return clone $this;
    }

    public function  __call($name,  $arguments)
    {
        //catch for methods which named like reserved words in php
        if($name === 'do') {
            return  call_user_func_array(array($this, '_gmcDo'), $arguments);
        } elseif ($name === 'echo') {
            return $this->_gmcEcho($arguments[0]);
        } elseif($name === 'errorCallback') {
            $this->_errorText = $arguments[0];
            $this->_errorNumber = $arguments[1];
        } else {
            $msg = 'function not available.';
            throw new GearmanPHP_Base_Exception($msg);
        }
    }

    /**
     * Add client options
     *
     * @param int $option
     * @return bool Always returns TRUE.
     */
    public function addOptions($option)
    {
        $this->_options[] = $option;
        return true;
    }

    /**
     * Add a job server to the client
     *
     * @param string $host The job server host name.
     * @param int $port The job server port.
     * @return bool
     */
    public function addServer($host = '127.0.0.1', $port = 4730)
    {
        if (empty($host) || !is_integer($port))
            return false;

        $this->_servers[] = array('host'=>$host, 'port'=>$port);
        return true;
    }

    /**
     * Add a list of job servers to the client
     *
     * @param string $servers A comma-separated list of servers, each server specified in the format 'host:port'.
     * @return bool
     */
    public function addServers($servers = '127.0.0.1:4730')
    {
        $servers = explode(",", $servers);
        foreach($servers as $server) {
            $server = explode(":", $server);
            $host = (isset($server[0]))?$server[0]:'127.0.0.1';
            $port = (isset($server[1]))?$server[1]:4730;
            $retval = $this->addServer($host, $port);
            if (!$retval) return false;
        }
        return true;
    }

    /**
     * Add a task to be run in parallel
     *
     * @param string $function_name A registered function the worker is to execute
     * @param string $workload Serialized data to be processed
     * @param mixed $context Application context to associate with a task
     * @param string $unique A unique ID used to identify a particular task
     * @return bool|GearmanPHP_GearmanTask
     */
    public function addTask($function_name, $workload, $context = null, $unique = null)
    {
        $task = $this->_getTask($function_name, $workload, $context, $unique, null);
        if ($task instanceof GearmanPHP_GearmanTask) {
            $this->_tasks[$task->uniqueId] = $task;
            return $task;
        } else {
            return false;
        }
    }

    /**
     * Add a background task to be run in parallel
     *
     * @param string $function_name A registered function the worker is to execute
     * @param string $workload Serialized data to be processed
     * @param mixed $context Application context to associate with a task
     * @param string $unique A unique ID used to identify a particular task
     * @return bool|GearmanPHP_GearmanTask
     */
    public function addTaskBackground($function_name, $workload, $context = null, $unique = null)
    {
        $task = $this->_getTask($function_name, $workload, $context, $unique, 'SUBMIT_JOB_BG');
        if ($task instanceof GearmanPHP_GearmanTask) {
            $this->_tasks[$task->uniqueId] = $task;
            return $task;
        } else {
            return false;
        }
    }

    /**
     * Add a high priority task to run in parallel
     *
     * @param string $function_name A registered function the worker is to execute
     * @param string $workload Serialized data to be processed
     * @param mixed $context Application context to associate with a task
     * @param string $unique A unique ID used to identify a particular task
     * @return bool|GearmanPHP_GearmanTask
     */
    public function addTaskHigh($function_name, $workload, $context = null, $unique = null)
    {
        $task = $this->_getTask($function_name, $workload, $context, $unique, 'SUBMIT_JOB_HIGH');
        if ($task instanceof GearmanPHP_GearmanTask) {
            $this->_tasks[$task->uniqueId] = $task;
            return $task;
        } else {
            return false;
        }
    }

    /**
     * Add a high priority background task to be run in parallel
     *
     * @param string $function_name A registered function the worker is to execute
     * @param string $workload Serialized data to be processed
     * @param mixed $context Application context to associate with a task
     * @param string $unique A unique ID used to identify a particular task
     * @return bool|GearmanPHP_GearmanTask
     */
    public function addTaskHighBackground($function_name, $workload, $context = null, $unique = null)
    {
        $task = $this->_getTask($function_name, $workload, $context, $unique, 'SUBMIT_JOB_HIGH_BG');
        if ($task instanceof GearmanPHP_GearmanTask) {
            $this->_tasks[$task->uniqueId] = $task;
            return $task;
        } else {
            return false;
        }
    }

    /**
     * Add a low priority task to run in parallel
     *
     * @param string $function_name A registered function the worker is to execute
     * @param string $workload Serialized data to be processed
     * @param mixed $context Application context to associate with a task
     * @param string $unique A unique ID used to identify a particular task
     * @return bool|GearmanPHP_GearmanTask
     */
    public function addTaskLow($function_name, $workload, $context = null, $unique = null)
    {
        $task = $this->_getTask($function_name, $workload, $context, $unique, 'SUBMIT_JOB_LOW');
        if ($task instanceof GearmanPHP_GearmanTask) {
            $this->_tasks[$task->uniqueId] = $task;
            return $task;
        } else {
            return false;
        }
    }

    /**
     * Add a low priority background task to be run in parallel
     *
     * @param string $function_name A registered function the worker is to execute
     * @param string $workload Serialized data to be processed
     * @param mixed $context Application context to associate with a task
     * @param string $unique A unique ID used to identify a particular task
     * @return bool|GearmanPHP_GearmanTask
     */
    public function addTaskLowBackground($function_name, $workload, $context = null, $unique = null)
    {
        $task = $this->_getTask($function_name, $workload, $context, $unique, 'SUBMIT_JOB_LOW_BG');
        if ($task instanceof GearmanPHP_GearmanTask) {
            $this->_tasks[$task->uniqueId] = $task;
            return $task;
        } else {
            return false;
        }
    }

    /**
     * Add a task to get status
     *
     * @param string $job_handle The job handle for the task to get status for
     * @param string $context Data to be passed to the status callback, generally a reference to an array or object
     * @return GearmanPHP_GearmanTask
     */
    public function addTaskStatus($job_handle, $context = null)
    {

    }

    /**
     * Clear all task callback functions
     *
     * @return bool Always returns TRUE.
     */
    public function clearCallbacks()
    {
        $this->_callbacks = array();
        return true;
    }

    /**
     * Get the application context
     *
     * @return string
     */
    public function context()
    {

    }

    /**
     * Get the application data (deprecated)
     *
     * @return string
     */
    public function data()
    {
        trigger_error("Deprecated function (" . __CLASS__ . "::" . __METHOD__ . ") called.", E_USER_NOTICE);
    }

    /**
     * Run a single task and return a result
     *
     * @param string $function_name A registered function the worker is to execute
     * @param string $workload Serialized data to be processed
     * @param string $unique A unique ID used to identify a particular task
     * @return string A string representing the results of running a task.
     */
    protected function _gmcDo($function_name, $workload, $unique = null, $jobType = null)
    {
        $task = $this->_getTask($function_name, $workload, null, $unique, 'SUBMIT_JOB');
        $this->_tasks[$task->uniqueId] = $task;
        $this->runTasks();
        return $this->_tasks[$task->uniqueId]->result;
    }

    /**
     * Run a task in the background
     *
     * @param string $function_name A registered function the worker is to execute
     * @param string $workload Serialized data to be processed
     * @param string $unique A unique ID used to identify a particular task
     * @return string The job handle for the submitted task.
     */
    public function doBackground($function_name, $workload, $unique = null)
    {
        $task = $this->_getTask($function_name, $workload, null, $unique, 'SUBMIT_JOB_BG');
        $this->_tasks[$task->uniqueId] = $task;
        $this->runTasks();
        return $this->_tasks[$task->uniqueId]->handle;
    }

    /**
     * Run a single high priority task
     *
     * @param string $function_name A registered function the worker is to execute
     * @param string $workload Serialized data to be processed
     * @param string $unique A unique ID used to identify a particular task
     * @return string The job handle for the submitted task.
     */
    public function doHigh($function_name, $workload, $unique = null)
    {
        $task = $this->_getTask($function_name, $workload, null, $unique, 'SUBMIT_JOB_HIGH');
        $this->_tasks[$task->uniqueId] = $task;
        $this->runTasks();
        return $this->_tasks[$task->uniqueId]->handle;
    }

    /**
     * Run a high priority task in the background
     *
     * @param string $function_name A registered function the worker is to execute
     * @param string $workload Serialized data to be processed
     * @param string $unique A unique ID used to identify a particular task
     * @return string The job handle for the submitted task.
     */
    public function doHighBackground($function_name, $workload, $unique = null)
    {
        $task = $this->_getTask($function_name, $workload, null, $unique, 'SUBMIT_JOB_HIGH_BG');
        $this->_tasks[$task->uniqueId] = $task;
        $this->runTasks();
        return $this->_tasks[$task->uniqueId]->handle;
    }

    /**
     * Run a single low priority task
     *
     * @param string $function_name A registered function the worker is to execute
     * @param string $workload Serialized data to be processed
     * @param string $unique A unique ID used to identify a particular task
     * @return string The job handle for the submitted task.
     */
    public function doLow($function_name, $workload, $unique = null)
    {
        $task = $this->_getTask($function_name, $workload, null, $unique, 'SUBMIT_JOB_LOW');
        $this->_tasks[$task->uniqueId] = $task;
        $this->runTasks();
        return $this->_tasks[$task->uniqueId]->handle;
    }

    /**
     * Run a low priority task in the background
     *
     * @param string $function_name A registered function the worker is to execute
     * @param string $workload Serialized data to be processed
     * @param string $unique A unique ID used to identify a particular task
     * @return string The job handle for the submitted task.
     */
    public function doLowBackground($function_name, $workload, $unique = null)
    {
        $task = $this->_getTask($function_name, $workload, null, $unique, 'SUBMIT_JOB_LOW_BG');
        $this->_tasks[$task->uniqueId] = $task;
        $this->runTasks();
        return $this->_tasks[$task->uniqueId]->handle;
    }

    /**
     * Get the job handle for the running task
     *
     * @return string The job handle for the running task.
     */
    public function doJobHandle()
    {
        return $this->_jobHandle;
    }

    /**
     * Get the status for the running task
     *
     * @return array An array representing the percentage completion given as a fraction, with the first element the numerator and the second element the denomintor.
     */
    public function doStatus()
    {
        return array($this->_numerator, $this->_denominator);
    }

    /**
     * Send data to all job servers to see if they echo it back
     *
     * @param string $workload Some arbitrary serialized data to be echo back
     * @return bool
     */
    protected function _gmEcho($workload)
    {
        $params = array(
            'text' => $workload
        );

        $socket = $this->_openConnection();
        GearmanPHP_Base_Common::sendCommand($socket, 'ECHO_REQ', $params);
        $response = GearmanPHP_Base_Common::getResponse($socket);

        if (isset($response['data']['text'])) {
            return $response['data']['text'];
        } else {
            return $response;
        }

        $this->_closeConnection($socket);
    }

    /**
     * Returns an error string for the last error encountered.
     *
     * @return string
     */
    public function error()
    {
        return $this->_errorText;
    }

    /**
     * Get an errno value
     *
     * @return
     */
    public function getErrno()
    {
        return $this->_errorNumber;
    }

    /**
     * Get the status of a background job
     *
     * @param string $job_handle The job handle assigned by the Gearman server
     * @return array An array containing status information for the job corresponding to the supplied job handle. The first array element is a boolean indicating whether the job is even known, the second is a boolean indicating whether the job is still running, and the third and fourth elements correspond to the numerator and denominator of the fractional completion percentage, respectively.
     */
    public function jobStatus($job_handle)
    {
        $params = array(
            'handle' => $job_handle
        );

        $socket = $this->_openConnection();
        GearmanPHP_Base_Common::sendCommand($socket, 'GET_STATUS', $params);
        $response = GearmanPHP_Base_Common::getResponse($socket);

        if (isset($response['data']['handle'])) {
            $return = array (
                (bool)$response['data']['known'],
                (bool)$response['data']['running'],
                (float)$response['data']['numerator'],
                (float)$response['data']['denominator']
            );
            return $return;
        } else {
            return $response;
        }

        $this->_closeConnection($socket);
    }

    /**
     * Remove client options
     *
     * @param int $options The options to be removed (unset)
     * @return bool Always returns TRUE.
     */
    public function removeOptions($options)
    {

    }

    /**
     * Get the last Gearman return code
     *
     * @return int A valid Gearman return code.
     */
    public function returnCode()
    {
         return $this->_lastReturnCode;
    }

    /**
     * Run a list of tasks in parallel
     *
     * @return bool
     */
    public function runTasks()
    {
        $this->_taskCount = $this->_getTasksArrayCount();
        $taskKeys   = $this->_getTasksArrayKeys();
        $t          = 0;
        $taskCount = $this->_taskCount;

        if ($this->_timeout !== null){
            $socket_timeout = min(10, (int)$this->_timeout);
        } else {
            $socket_timeout = 10;
        }
        while (0 < $this->_taskCount) {
            if ($this->_timeout !== null) {
                if (empty($start)) {
                    $start = microtime(true);
                } else {
                    $now = microtime(true);
                    if ($now - $start >= $this->_timeout) {
                        break;
                    }
                }
            }


            if ($t < $taskCount) {
                $k = $taskKeys[$t];
                $this->_submitTask($this->_tasks[$k]);
                if (strpos($this->_tasks[$k]->command, "_BG") !== false) {
                    $this->_tasks[$k]->finished = true;
                    $this->_taskCount--;
                }
                $t++;
            }

            $write  = null;
            $except = null;
            $read   = $this->_sockets;
            socket_select($read, $write, $except, $socket_timeout);
            foreach ($read as $socket) {
                $response = GearmanPHP_Base_Common::read($socket);
                if (count($response)) {
                    $this->_handleResponse($response, $socket);
                }
            }
        }
        return true;
    }

    /**
     * Callback function when there is a data packet for a task (deprecated)
     *
     * @param GearmanPHP_GearmanTask $callback A function or method to call
     * @return bool
     */
    public function setClientCallback($callback)
    {
        trigger_error("Deprecated function (" . __CLASS__ . "::" . __METHOD__ . ") called.", E_USER_NOTICE);
    }

    /**
     * Set a function to be called on task completion
     *
     * @param callback $callback A function to be called
     * @return bool
     */
    public function setCompleteCallback($callback)
    {
        $this->_attachCallback($callback, self::TASK_COMPLETE);
    }

    /**
     * Set application context
     *
     * @param string $context Arbitrary context data
     * @return bool Always returns TRUE.
     */
    public function setContext($context)
    {
        //TODO
    }

    /**
     * Set a callback for when a task is queued
     *
     * @param callback $callback A function to call
     * @return bool
     */
    public function setCreatedCallback($callback)
    {
        $this->_attachCallback($callback, self::TASK_CREATED);
    }

    /**
     * Set application data (deprecated)
     *
     * @param string $data
     * @return bool Always returns TRUE.
     */
    public function setData($data)
    {
        trigger_error("Deprecated function (" . __CLASS__ . "::" . __METHOD__ . ") called.", E_USER_NOTICE);
        return true;
    }

    /**
     * Callback function when there is a data packet for a task
     *
     * @param callback $callback A function or method to call
     * @return bool
     */
    public function setDataCallback($callback)
    {
        $this->_attachCallback($callback, self::TASK_DATA);
    }

    /**
     * Set a callback for worker exceptions
     *
     * @param callback $callback Function to call when the worker throws an exception
     * @return bool
     */
    public function setExceptionCallback($callback)
    {
        $this->_attachCallback($callback, self::TASK_EXCEPTION);
    }

    /**
     * Set callback for job failure
     *
     * @param callback $callback Function to call when the worker throws an exception
     * @return bool
     */
    public function setFailCallback($callback)
    {
        $this->_attachCallback($callback, self::TASK_FAIL);
    }

    /**
     * Set client options
     *
     * @param int $options The options to be set
     * @return bool Always returns TRUE.
     */
    public function setOptions($options)
    {
        $socket = $this->_openConnection();
        foreach($this->_options as $option) {
            GearmanPHP_Base_Common::sendCommand($socket, 'OPTION_REQ', array('option' => $option));
            $response = GearmanPHP_Base_Common::getResponse($socket);
        }
    }

    /**
     * Set a callback for collecting task status
     *
     * @param callback $callback Function to call when the worker throws an exception
     * @return bool
     */
    public function setStatusCallback($callback)
    {
        $this->_attachCallback($callback, self::TASK_STATUS);
    }

    /**
     * Set socket I/O activity timeout
     *
     * @param int $timeout An interval of time in milliseconds
     * @return bool Always returns TRUE.
     */
    public function setTimeout($timeout)
    {
        $this->_timeout = $timeout;
    }

    /**
     * Set a callback for worker warnings
     *
     * @param callback $callback Function to call when the worker throws an exception
     * @return bool
     */
    public function setWarningCallback($callback)
    {
        $this->_attachCallback($callback, self::TASK_WARNING);
    }

    /**
     * Set a callback for accepting incremental data updates
     *
     * @param callback $callback Function to call when the worker throws an exception
     * @return bool
     */
    public function setWorkloadCallback($callback)
    {
        $this->_attachCallback($callback, self::TASK_WORKLOAD);
    }

    /**
     * Get current socket I/O activity timeout value
     *
     * @return int Timeout in milliseconds to wait for I/O activity. A negative value means an infinite timeout.
     */
    public function timeout()
    {
        return $this->_timeout;
    }

    protected function _getTask($function_name, $workload, $context = null, $unique = null, $command = null)
    {
        $task = new GearmanPHP_GearmanTask();

        $task->functionName = $function_name;
        $task->workload = $workload;

        if(!is_scalar($workload)){
            $task->workload = json_encode($workload);
        } else {
            $task->workload = $workload;
        }

        if(!is_null($context))
            $task->context = $context;

        if(!is_null($unique)) {
            $task->uniqueId = $unique;
        } else {
            $task->uniqueId = uniqid();
        }

        if(!is_null($command)) {
            $task->command = $command;
        } else {
            $task->command = 'SUBMIT_JOB';
        }

        return $task;
    }

    protected function _submitTask(GearmanPHP_GearmanTask $task)
    {
        $socket = $this->_openConnection();

        $this->_sockets[] = $socket;

        $params = array(
            'func' => $task->functionName,
            'uniq' => $task->uniqueId,
            'arg'  => $task->workload
        );

        GearmanPHP_Base_Common::sendCommand($socket, $task->command, $params);

        $socket_key = $this->_getSocketAddr($socket);
        if (!$socket_key) {
            return;
        }
        if (!is_array(GearmanPHP_Base_Common::$waiting[$socket_key])) {
            GearmanPHP_Base_Common::$waiting[$socket_key] = array();
        }

        array_push(GearmanPHP_Base_Common::$waiting[$socket_key], $task);
    }

    protected function _getSocketAddr($socket) {
        if (is_resource($socket) && strtolower(get_resource_type($socket)) == 'socket') {
            return intval($socket);
        }
        return false;
    }

        /**
     * Handle the response read in
     *
     * @param array    $resp  The raw array response
     * @param resource $s     The socket
     * @param object   $tasks The tasks being ran
     *
     * @return void
     * @throws GearmanPHP_Base_Exception
     */
    protected function _handleResponse($response, $socket)
    {
        if (isset($response['data']['handle']) && $response['function'] != 'JOB_CREATED') {
            $handle = $response['data']['handle'];
            if (array_key_exists($handle, $this->_handles)) {
                $task = $this->_tasks[$this->_handles[$handle]];
            }
        }

        switch ($response['function']) {
            case 'WORK_COMPLETE':
                $task->finished = true;
                $task->result = $response['data']['result'];
                $this->_taskCount--;
                $task->running = false;
                $task->lastReturnCode = GEARMAN_SUCCESS;
                $this->_lastReturnCode = GEARMAN_SUCCESS;
                $this->_fireCallbacks($task, self::TASK_COMPLETE);
                break;
            case 'WORK_STATUS':
                $task->numerator = (int)$response['data']['numerator'];
                $task->denominator = (int)$response['data']['denominator'];
                $this->_numerator = $task->numerator;
                $this->_denominator = $task->denominator;
                $task->lastReturnCode = GEARMAN_WORK_STATUS;
                $this->_lastReturnCode = GEARMAN_WORK_STATUS;
                $this->_fireCallbacks($task, self::TASK_STATUS);
                break;
            case 'WORK_FAIL':
                $this->_taskCount--;
                $task->running = false;
                $task->lastReturnCode = GEARMAN_WORK_FAIL;
                $this->_lastReturnCode = GEARMAN_WORK_FAIL;
                $this->_fireCallbacks($task, self::TASK_FAIL);
                break;
            case 'WORK_WARNING':
                $task->lastReturnCode = GEARMAN_WORK_WARNING;
                $this->_lastReturnCode = GEARMAN_WORK_WARNING;
                $this->_fireCallbacks($task, self::TASK_WARNING);
                break;
            case 'WORK_EXCEPTION':
                $this->_taskCount--;
                $task->running = false;
                $task->lastReturnCode = GEARMAN_WORK_EXCEPTION;
                $this->_lastReturnCode = GEARMAN_WORK_EXCEPTION;
                $this->_fireCallbacks($task, self::TASK_EXCEPTION);
                break;
            case 'WORK_DATA':
                if (isset($response['data']['result'])) {
                    $task->result = $response['data']['result'];
                } else {
                    $task->result = null;
                }
                $task->lastReturnCode = GEARMAN_WORK_DATA;
                $this->_lastReturnCode = GEARMAN_WORK_DATA;
                $this->_fireCallbacks($task, self::TASK_DATA);
                break;
            case 'JOB_CREATED':
                $socket_key = $this->_getSocketAddr($socket);
                $task = array_shift(GearmanPHP_Base_Common::$waiting[$socket_key]);
                $task->handle = $response['data']['handle'];
                $task->known = true;
                $task->running = true;
                if (strpos($task->command, "_BG") !== false) {
                    $task->finished = true;
                }
                $this->_handles[$task->handle] = $task->uniqueId;
                $this->_numerator = 1;
                $this->_denominator = 1;
                $this->_lastReturnCode = null;
                $this->_jobHandle = $task->handle;
                break;
            case 'ERROR':
                throw new GearmanPHP_Base_Exception('An error occurred');
            default:
                throw new GearmanPHP_Base_Exception('Invalid function ' . $response['function']);
        }
    }

    /**
     * Attach a callback to this task
     *
     * @param callback $callback A valid PHP callback
     * @param integer  $type     Type of callback
     *
     * @return void
     * @throws GearmanPHP_Base_Exception
     */
    protected function _attachCallback($callback, $type = self::TASK_COMPLETE)
    {
        if (!is_callable($callback)) {
            throw new GearmanPHP_Base_Exception('Invalid callback specified');
        }

        $this->_callbacks[$type][] = $callback;
    }

    /**
     * Run the callbacks
     *
     * Complete callbacks are passed the name of the job, the handle of the
     * job and the result of the job (in that order).
     *
     * @param GearmanPHP_Task $task a GearmanTask object
     * @param int $type callbacl type
     *
     * @return void
     * @see GearmanPHP_GearmanClient::_attachCallback()
     */
    protected function _fireCallbacks($task, $type)
    {
        if (!count($this->_callbacks[$type])) {
            return;
        }

        foreach ($this->_callbacks[$type] as $callback) {
            call_user_func($callback, $task);
        }
    }

    /**
     * get count of all tasks not finished yet
     *
     * @return int count of tasks not finished yet
     */
    protected function _getTasksArrayCount()
    {
        $count = 0;
        foreach ($this->_tasks as $task) {
            if ($task->finished != true) {
                $count++;
            }
        }
        return $count;
    }

    /**
     * get arraykeys (uniqueid) of all tasks not finished yet
     *
     * @return int arraykeys of tasks not finished yet
     */
    protected function _getTasksArrayKeys()
    {
        $keys = array();
        foreach ($this->_tasks as $key => $task) {
            if ($task->finished != true) {
                $keys[] = $key;
            }
        }
        return $keys;
    }
}