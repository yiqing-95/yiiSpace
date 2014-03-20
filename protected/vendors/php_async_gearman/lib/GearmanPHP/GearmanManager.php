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
/**
 * Represents a class for connecting to a Gearman job server and making
 * requests to perform some function on provided data. The function performed
 * must be one registerd by a Gearman worker and the data passed is opaque
 * to the job server.
 *
 * @package       GearmanPHP
 */
class GearmanPHP_GearmanManager
{
    /**
     * Connection resource
     *
     * @var resource $conn Connection to Gearman server
     * @see GearmanPHP_GearmanManager::sendCommand()
     * @see GearmanPHP_GearmanManager::recvCommand()
     */
    protected $conn = null;

    /**
     * The server is shutdown
     *
     * We obviously can't send more commands to a server after it's been shut
     * down. This is set to true in GearmanPHP_GearmanManager::shutdown() and then
     * checked in GearmanPHP_GearmanManager::sendCommand().
     *
     * @var boolean $shutdown
     */
    protected $shutdown = false;

    /**
     * Constructor
     *
     * @param string  $server  Host and port (e.g. 'localhost:4730')
     * @param integer $timeout Connection timeout
     *
     * @throws GearmanPHP_Base_Exception
     * @see GearmanPHP_GearmanManager::$conn
     */
    public function __construct($server, $timeout = 5)
    {
        if (strpos($server, ':')) {
            list($host, $port) = explode(':', $server);
        } else {
            $host = $server;
            $port = 4730;
        }

        $errCode    = 0;
        $errMsg     = '';
        
        $this->conn = fsockopen($host, $port, $errCode, $errMsg, $timeout);
        if ($this->conn === false) {
            throw new GearmanPHP_Base_Exception(
                'Could not connect to ' . $host . ':' . $port
            );
        }
    }

    /**
     * Get the version of Gearman running
     *
     * @return string
     * @see GearmanPHP_GearmanManager::sendCommand()
     * @see GearmanPHP_GearmanManager::checkForError()
     */
    public function version()
    {
        $this->sendCommand('version');
        $res = fgets($this->conn, 4096);
        $this->checkForError($res);
        return trim($res);
    }

    /**
     * Shut down Gearman
     *
     * @param boolean $graceful Whether it should be a graceful shutdown
     *
     * @return boolean
     * @see GearmanPHP_GearmanManager::sendCommand()
     * @see GearmanPHP_GearmanManager::checkForError()
     * @see GearmanPHP_GearmanManager::$shutdown
     */
    public function shutdown($graceful = false)
    {
        $cmd = ($graceful) ? 'shutdown graceful' : 'shutdown';
        $this->sendCommand($cmd);
        $res = fgets($this->conn, 4096);
        $this->checkForError($res);

        $this->shutdown = (trim($res) == 'OK');
        return $this->shutdown;
    }

    /**
     * Get worker status and info
     *
     * Returns the file descriptor, IP address, client ID and the abilities
     * that the worker has announced.
     *
     * @return array A list of workers connected to the server
     * @throws GearmanPHP_Base_Exception
     */
    public function workers()
    {
        $this->sendCommand('workers');
        $res     = $this->recvCommand();
        $workers = array();
        $tmp     = explode("\n", $res);
        foreach ($tmp as $t) {
            if (!GearmanPHP_Base_Common::stringLength($t)) {
                continue;
            }

            list($info, $abilities) = explode(" : ", $t);
            list($fd, $ip, $id)     = explode(' ', $info);

            $abilities = trim($abilities);

            $workers[] = array(
                'fd' => $fd,
                'ip' => $ip,
                'id' => $id,
                'abilities' => empty($abilities) ? array() : explode(' ', $abilities)
            );
        }

        return $workers;
    }

    /**
     * Set maximum queue size for a function
     *
     * For a given function of job, the maximum queue size is adjusted to be
     * max_queue_size jobs long. A negative value indicates unlimited queue
     * size.
     *
     * If the max_queue_size value is not supplied then it is unset (and the
     * default maximum queue size will apply to this function).
     *
     * @param string  $function Name of function to set queue size for
     * @param integer $size     New size of queue
     *
     * @return boolean
     * @throws GearmanPHP_Base_Exception
     */
    public function setMaxQueueSize($function, $size)
    {
        if (!is_numeric($size)) {
            throw new GearmanPHP_Base_Exception('Queue size must be numeric');
        }

        if (preg_match('/[^a-z0-9_]/i', $function)) {
            throw new GearmanPHP_Base_Exception('Invalid function name');
        }

        $this->sendCommand('maxqueue ' . $function . ' ' . $size);
        $res = fgets($this->conn, 4096);
        $this->checkForError($res);
        return (trim($res) == 'OK');
    }

    /**
     * Get queue/worker status by function
     *
     * This function queries for queue status. The array returned is keyed by
     * the function (job) name and has how many jobs are in the queue, how
     * many jobs are running and how many workers are capable of performing
     * that job.
     *
     * @return array An array keyed by function name
     * @throws GearmanPHP_Base_Exception
     */
    public function status()
    {
        $this->sendCommand('status');
        $res = $this->recvCommand();

        $status = array();
        $tmp    = explode("\n", $res);
        foreach ($tmp as $t) {
            if (!GearmanPHP_Base_Common::stringLength($t)) {
                continue;
            }

            list($func, $inQueue, $jobsRunning, $capable) = explode("\t", $t);

            $status[$func] = array(
                'in_queue' => $inQueue,
                'jobs_running' => $jobsRunning,
                'capable_workers' => $capable
            );
        }

        return $status;
    }

    /**
     * Send a command
     *
     * @param string $cmd The command to send
     *
     * @return void
     * @throws GearmanPHP_Base_Exception
     */
    protected function sendCommand($cmd)
    {
        if ($this->shutdown) {
            throw new GearmanPHP_Base_Exception('This server has been shut down');
        }

        fwrite($this->conn,
               $cmd . "\r\n",
               GearmanPHP_Base_Common::stringLength($cmd . "\r\n"));
    }

    /**
     * Receive a response
     *
     * For most commands Gearman returns a bunch of lines and ends the
     * transmission of data with a single line of ".\n". This command reads
     * in everything until ".\n". If the command being sent is NOT ended with
     * ".\n" DO NOT use this command.
     *
     * @throws GearmanPHP_Base_Exception
     * @return string
     */
    protected function recvCommand()
    {
        $ret = '';
        while (true) {
            $data = fgets($this->conn, 4096);
            $this->checkForError($data);
            if ($data == ".\n") {
                break;
            }

            $ret .= $data;
        }

        return $ret;
    }

    /**
     * Check for an error
     *
     * Gearman returns errors in the format of 'ERR code_here Message+here'.
     * This method checks returned values from the server for this error format
     * and will throw the appropriate exception.
     *
     * @param string $data The returned data to check for an error
     *
     * @return void
     * @throws GearmanPHP_Base_Exception
     */
    protected function checkForError($data)
    {
        $data = trim($data);
        if (preg_match('/^ERR/', $data)) {
            list(, $code, $msg) = explode(' ', $data);
            throw new GearmanPHP_Base_Exception($msg, urldecode($code));
        }
    }

    /**
     * Disconnect from server
     *
     * @return void
     * @see GearmanPHP_GearmanManager::$conn
     */
    public function disconnect()
    {
        if (is_resource($this->conn)) {
            fclose($this->conn);
        }
    }

    /**
     * Destructor
     *
     * @return void
     */
    public function __destruct()
    {
        $this->disconnect();
    }
}