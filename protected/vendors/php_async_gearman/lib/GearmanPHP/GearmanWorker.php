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
require_once 'GearmanJob.php';
/**
 * Represents a class for connecting to a Gearman job server and making
 * requests to perform some function on provided data. The function performed
 * must be one registerd by a Gearman worker and the data passed is opaque
 * to the job server.
 *
 * @package       GearmanPHP
 */
class GearmanPHP_GearmanWorker
{

    /**
     * Unique id for this worker
     *
     * @var string $id
     */
    protected $_workerId = "";

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
     * Pool of retry connections
     *
     * @var array $conn
     */
    protected $_retryServers = array();

    /**
     * The timeout for Gearman connections
     *
     * @var integer $timeout
     */
    protected $_connectionTimeout = 1000;

    /**
     * Callbacks registered
     *
     * @var array $callbacks
     */
    protected $_callbacks = array();

    protected $_errorText = null;
    protected $_errorNumber = null;

    protected $_lastReturnCode = null;

    protected $_workerFunctions = array();

    protected $_workerOptions = array();

    /**
     * Creates a GearmanClient instance representing a client that connects
     * to the job server and submits tasks to complete.
     *
     * @return GearmanPHP_GearmanClient
     */
    public function  __construct()
    {
        $this->_workerId = "pid_".getmypid()."_".uniqid();
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
        if($name === 'echo') {
            return $this->_gmwEcho($arguments[0]);
        } elseif($name === 'errorCallback') {
            $this->_errorText = $arguments[0];
            $this->_errorNumber = $arguments[1];
        } else {
            $msg = 'function not available.';
            throw new GearmanPHP_Base_Exception($msg);
        }
    }

    /**
     * Get a connection to a Gearman server
     *
     * @return void
     */
    protected function _openConnections()
    {

        foreach($this->_servers as $serverKey => $serverEntry) {
            $server = $serverEntry['host'] . ':' . $serverEntry['port'];
            if (!isset($this->_sockets[$server]) || !is_resource($this->_sockets[$server])) {
                try {
                    $socket = null;
                    $socket = GearmanPHP_Base_Common::connect($server, $this->_connectionTimeout);

                    GearmanPHP_Base_Common::sendCommand($socket, "SET_CLIENT_ID", array("client_id" => $this->_workerId));

                    if (GearmanPHP_Base_Common::isConnected($socket)) {
                        GearmanPHP_Base_Common::addErrorCallback(array($this, 'errorCallback'));
                        $this->_sockets[$server] = $socket;
                    }

                } catch (GearmanPHP_Base_Exception $e) {

                    $this->retryServers[$server] = time();
                }
            }
        }
    }

    /**
     * Destructor
     *
     * @return void
     */
    public function __destruct()
    {
        $this->unregisterAll();
        foreach($this->_sockets as $socket) {
            GearmanPHP_Base_Common::close($socket);
        }
    }

    /**
     * Registers a function name with the job server and specifies a callback
     * corresponding to that function. Optionally specify extra application
     * context data to be used when the callback is called and a timeout.
     *
     * @param string $function_name The name of a function to register with the job server
     * @param callback $function A callback that gets called when a job for the registered function name is submitted
     * @param mixed $context A reference to arbitrary application context data that can be modified by the worker function
     * @param int $timeout An interval of time in seconds
     * @return bool Returns TRUE on success or FALSE on failure.
     *
     * @since 0.5.0
     */
    public function addFunction($function_name , $function , &$context = null, $timeout = 0)
    {
        $this->_openConnections();

        if($this->register($function_name, $timeout)) {
            $this->_workerFunctions[$function_name] = $timeout;
            $this->_callbacks[$function_name] = $function;
            return true;
        }
        return false;
    }

    /**
     * Add worker options
     *
     * @param int $option The options to be added
     * @return bool Always returns TRUE.
     */
    public function addOptions($option)
    {
        $this->_workerOptions[$option] = 1;
        return true;
    }

    /**
     * Adds a job server to this worker. This goes into a list of servers
     * than can be used to run jobs. No socket I/O happens here.
     *
     * @param string $host The job server host name.
     * @param int $port The job server port.
     * @return bool Returns TRUE on success or FALSE on failure.
     */
    public function addServer($host = '127.0.0.1', $port = 4730)
    {
        if (empty($host) || !is_integer($port))
            return false;

        $this->_servers[] = array('host'=>$host, 'port'=>$port);
        return true;
    }

    /**
     * Adds one or more job servers to this worker.
     * These go into a list of servers that can be used to run jobs.
     * No socket I/O happens here.
     *
     * @param string $servers A comma separated list of job servers in the format host:port. If no port is specified, it defaults to 4730.
     * @return bool Returns TRUE on success or FALSE on failure.
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
     * Sends data to all job servers to see if they echo it back.
     * This is a test function to see if job servers are responding properly.
     *
     * @param string $workload Arbitrary serialized data
     * @return bool Returns TRUE on success or FALSE on failure.
     */
    protected function _gmwEcho($workload)
    {
        $params = array(
            'text' => json_encode($workload)
        );

        $this->_openConnections();
        foreach($this->_sockets as $socket) {
            GearmanPHP_Base_Common::sendCommand($socket, 'ECHO_REQ', $params);
            $response = GearmanPHP_Base_Common::getResponse($socket);
            if (!isset($response['data']['text']) || $response['data']['text'] != json_decode($workload)) {
                return false;
            }
            GearmanPHP_Base_Common::close($socket);
        }
        return true;
    }

    /**
     * Returns and error string for the last error encountered.
     *
     * @return string An error string.
     */
    public function error()
    {
        return $this->_errorText;
    }

    /**
     * Returns the value of errno in the case of a GEARMAN_ERRNO return value.
     *
     * @return int A valid errno.
     */
    public function getErrno()
    {
        return $this->_errorNumber;
    }

    /**
     * Gets the options previously set for the worker.
     *
     * @return int The options currently set for the worker.
     */
    public function options()
    {

    }

    /**
     * Registers a function name with the job server with an optional timeout.
     * The timeout specifies how many seconds the server will wait before
     * marking a job as failed.
     * If the timeout is set to zero, there is no timeout.
     *
     * @param string $function_name The name of a function to register with the job server
     * @param int $timeout An interval of time in seconds
     * @return bool
     */
    public function register($function_name, $timeout = 0)
    {
        $this->_openConnections();

        if(is_int($timeout) && $timeout > 0) {
            $command = 'CAN_DO_TIMEOUT';
            $params = array('func' => $function_name, 'timeout' => $timeout);
        } else {
            $command = 'CAN_DO';
            $params = array('func' => $function_name);
        }

        foreach ($this->_sockets as $socket) {
            try {
                GearmanPHP_Base_Common::sendCommand($socket, $command, $params);
            } catch (GearmanPHP_Base_Exception $e) {
                echo $e->getMessage();
                return false;
            }
        }
        return true;
    }

    /**
     * Removes (unsets) one or more worker options.
     *
     * @param int $option The options to be removed (unset)
     * @return bool Always returns TRUE.
     */
    public function removeOptions($option)
    {
        $this->_workerOptions[$option] = null;
        return true;
    }

    /**
     * Returns the last Gearman return code.
     *
     * @return int A valid Gearman return code.
     */
    public function returnCode()
    {
        switch ($this->_lastReturnCode) {

            case 'GEARMAN_IO_WAIT':
                return GEARMAN_IO_WAIT;
            case 'GEARMAN_WORK_DATA':
                return GEARMAN_WORK_DATA;
            case 'GEARMAN_WORK_WARNING':
                return GEARMAN_WORK_WARNING;
            case 'GEARMAN_WORK_STATUS':
                return GEARMAN_WORK_STATUS;
            case 'GEARMAN_WORK_EXCEPTION':
                return GEARMAN_WORK_EXCEPTION;
            case 'GEARMAN_WORK_FAIL':
                return GEARMAN_WORK_FAIL;
            case 'GEARMAN_NO_ACTIVE_FDS':
                return GEARMAN_NO_ACTIVE_FDS;
            case 'GEARMAN_NO_JOBS':
                return GEARMAN_NO_JOBS;
            default:
                return GEARMAN_SUCCESS;

        }
    }

    /**
     * Sets one or more options to the supplied value.
     *
     * @param int $option The options to be set
     * @return bool Always returns TRUE.
     */
    public function setOptions($option)
    {
        $this->_workerOptions[$option] = 1;
        return true;
    }

    /**
     * Sets the interval of time to wait for socket I/O activity.
     *
     * @param int $timeout An interval of time in milliseconds. A negative value indicates an infinite timeout.
     * @return bool Always returns TRUE.
     */
    public function setTimeout($timeout)
    {
        $this->_connectionTimeout = $timeout;
        return true;
    }

    /**
     * Returns the current time to wait, in milliseconds, for socket I/O activity.
     *
     * @return int A time period is milliseconds. A negative value indicates an infinite timeout.
     */
    public function timeout()
    {
        return $this->_connectionTimeout;
    }

    /**
     * Unregisters a function name with the job servers ensuring that
     * no more jobs (for that function) are sent to this worker.
     *
     * @param string $function_name The name of a function to register with the job server
     * @return bool
     */
    public function unregister($function_name)
    {
        $return = true;
        $params = array('func' => $function_name);
        foreach ($this->_sockets as $socket) {
            $return = GearmanPHP_Base_Common::sendCommand($socket, 'CANT_DO', $params);
            if ($return != true) break;
        }
        unset($this->_workerFunctions[$function_name]);
        return $return;
    }

    /**
     * Unregister all function names with the job servers
     *
     * @return bool
     */
    public function unregisterAll()
    {
        $return = true;
        foreach ($this->_sockets as $socket) {
            $return = GearmanPHP_Base_Common::sendCommand($socket, 'RESET_ABILITIES', array());
            if ($return != true) break;
        }
        $this->_workerFunctions = array();
        return $return;
    }

    /**
     * Causes the worker to wait for activity from one of the Gearman
     * job servers when operating in non-blocking I/O mode. On failure,
     * issues a E_WARNING with the last Gearman error encountered.
     *
     * @return bool Returns TRUE on success or FALSE on failure.
     */
    public function wait()
    {

        foreach ($this->_sockets as $server => $socket) {
            GearmanPHP_Base_Common::close($socket);
            unset($this->_sockets[$server]);
        }

        if (count($this->_sockets) == 0) {
            $this->_lastReturnCode = 'GEARMAN_NO_ACTIVE_FDS';
            return false;
        } else {
            $this->_lastReturnCode = 'GEARMAN_NO_ACTIVE_FDS';
            return false;
        }
    }

    /**
     * Waits for a job to be assigned and then calls the appropriate callback
     * function. Issues an E_WARNING with the last Gearman error if the return
     * code is not one of GEARMAN_SUCCESS, GEARMAN_IO_WAIT, or GEARMAN_WORK_FAIL.
     *
     * @return bool Returns TRUE on success or FALSE on failure.
     */
    public function work()
    {
        $write       = null;
        $except      = null;
        $sleep       = true;
        $currentTime = time();
        $retryTime   = 5;
        $socketBlock = (isset($this->_workerOptions[GEARMAN_WORKER_NON_BLOCKING]) && $this->_workerOptions[GEARMAN_WORKER_NON_BLOCKING] == 1)?0:null;

        if (isset($this->_workerOptions[GEARMAN_WORKER_NON_BLOCKING]) && $this->_workerOptions[GEARMAN_WORKER_NON_BLOCKING] == 1) {
            $this->_openConnections();
            foreach ($this->_workerFunctions as $function_name => $timeout) {
                $this->register($function_name, $timeout);
            }
        }

        foreach ($this->_sockets as $server => $socket) {
            try {
                $worked = $this->_getJobAndRun($socket);
            } catch (GearmanPHP_Base_Exception $e) {
                unset($this->_sockets[$server]);
                $this->_retryServers[$server] = $currentTime;
            }

            if ($worked) {
                $sleep   = false;
            } elseif (isset($this->_workerOptions[GEARMAN_WORKER_NON_BLOCKING]) && $this->_workerOptions[GEARMAN_WORKER_NON_BLOCKING] == 1) {
                return false;
            }
        }

        if ($sleep && count($this->_sockets)) {
            foreach ($this->_sockets as $socket) {
                GearmanPHP_Base_Common::sendCommand($socket, 'PRE_SLEEP');
            }
            $this->_lastReturnCode = 'GEARMAN_SUCCESS';
            $read = $this->_sockets;
            socket_select($read, $write, $except, $socketBlock);
        }

        $retryChange = false;
        foreach ($this->_retryServers as $serverEntry => $lastTry) {
            if (($lastTry + $retryTime) < $currentTime) {
                try {
                    $socket = GearmanPHP_Base_Common::connect($serverEntry);
                    $this->_sockets[$serverEntry] = $socket;
                    $retryChange = true;
                    unset($this->_retryServers[$serverEntry]);
                    GearmanPHP_Base_Common::sendCommand($socket, "SET_CLIENT_ID", array("client_id" => $this->_workerId));
                } catch (GearmanPHP_Base_Exception $e) {
                    $this->_retryServers[$serverEntry] = $currentTime;
                }
            }
        }

        if (count($this->_sockets) == 0) {
            // sleep to avoid wasted cpu cycles if no connections to block on using socket_select
            sleep(1);
        }

        if ($retryChange === true) {
            // broadcast all abilities to all servers
            foreach ($this->_workerFunctions as $function_name => $timeout) {
                $this->register($function_name, $timeout);
            }
        }
        return true;
    }

    /**
     * Listen on the socket for work
     *
     * Sends the 'GRAB_JOB_UNIQ' command and then listens for either the 'NOOP' or
     * the 'NO_JOB' command to come back. If the 'JOB_ASSIGN_UNIQ' comes down the
     * pipe then we run that job.
     *
     * @param resource $socket The socket to work on
     *
     * @return boolean Returns true if work was done, false if not
     * @throws GearmanPHP_Base_Exception
     * @see GearmanPHP_Base_Common::sendCommand()
     */
    protected function _getJobAndRun($socket)
    {
        GearmanPHP_Base_Common::sendCommand($socket, 'GRAB_JOB_UNIQ');

        $resp = array('function' => 'NOOP');
        while (count($resp) && $resp['function'] == 'NOOP') {
            $resp = GearmanPHP_Base_Common::blockingRead($socket);
        }

        if ($resp['function'] == 'NO_JOB') {
            $this->_lastReturnCode = 'GEARMAN_NO_JOBS';
            return false;
        } elseif ($resp['function'] == 'NOOP') {
            $this->_lastReturnCode = 'GEARMAN_IO_WAIT';
            return true;
        }

        if ($resp['function'] != 'JOB_ASSIGN_UNIQ') {
            throw new GearmanPHP_Base_Exception('Holy Cow! What are you doing?!');
        }

        $job = new GearmanPHP_GearmanJob();
        $job->socket = $socket;
        $job->functionName = $resp['data']['func'];
        $job->handle = $resp['data']['handle'];
        $job->uuid = $resp['data']['uniq'];

        $arg = array();
        if (isset($resp['data']['arg']) &&
            GearmanPHP_Base_Common::stringLength($resp['data']['arg'])) {
            $arg = json_decode($resp['data']['arg'], true);
            if($arg === null){
                $arg = $resp['data']['arg'];
            }
            $job->workload = $arg;
        }
        try {
            $result = call_user_func($this->_callbacks[$job->functionName], $job);
        } catch (Exception $e) {
            $job->sendFail();
        }
        $job->sendComplete($result);
        $this->_lastReturnCode = $job->returnCode();

        // Force the job's destructor to run
        $job = null;

        return true;
    }
}