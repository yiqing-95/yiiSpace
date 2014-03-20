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
require_once 'Constants.php';
require_once 'Exception.php';
/**
 * Represents a class for connecting to a Gearman job server and making
 * requests to perform some function on provided data. The function performed
 * must be one registerd by a Gearman worker and the data passed is opaque
 * to the job server.
 *
 * @package       GearmanPHP
 */
class GearmanPHP_Base_Common
{

    /**
     * A list of valid Gearman commands
     *
     * This is a list of valid Gearman commands (the key of the array), their
     * integery type (first key in second array) used in the binary header, and
     * the arguments / order of arguments to send/receive.
     *
     * @var array $commands
     * @see GearmanPHP_Base_Connection::$magic
     * @see GearmanPHP_Base_Connection::connect()
     * @see http://gearman.org/index.php?id=protocol
     */
    static protected $commands = array(
        'CAN_DO' => array(1, array('func')),
        'CANT_DO' => array(2, array('func')),
        'RESET_ABILITIES' => array(3, array()),
        'PRE_SLEEP' => array(4, array()),
        //5 (unused)
        'NOOP' => array(6, array()),
        'SUBMIT_JOB' => array(7, array('func', 'uniq', 'arg')),
        'JOB_CREATED' => array(8, array('handle')),
        'GRAB_JOB' => array(9, array()),
        'NO_JOB' => array(10, array()),
        'JOB_ASSIGN' => array(11, array('handle', 'func', 'arg')),
        'WORK_STATUS' => array(12, array('handle', 'numerator', 'denominator')),
        'WORK_COMPLETE' => array(13, array('handle', 'result')),
        'WORK_FAIL' => array(14, array('handle')),
        'GET_STATUS' => array(15, array('handle')),
        'ECHO_REQ' => array(16, array('text')),
        'ECHO_RES' => array(17, array('text')),
        'SUBMIT_JOB_BG' => array(18, array('func', 'uniq', 'arg')),
        'ERROR' => array(19, array('err_code', 'err_text')),
        'STATUS_RES' => array(20, array('handle', 'known', 'running', 'numerator', 'denominator')),
        'SUBMIT_JOB_HIGH' => array(21, array('func', 'uniq', 'arg')),
        'SET_CLIENT_ID' => array(22, array('client_id')),
        'CAN_DO_TIMEOUT' => array(23, array('func', 'timeout')),
        'ALL_YOURS' => array(24, array()),
        'WORK_EXCEPTION' => array(25, array('handle', 'result')),
        'OPTION_REQ' => array(26, array('option')),
        'OPTION_RES' => array(27, array('option')),
        'WORK_DATA' => array(28, array('handle', 'result')),
        'WORK_WARNING' => array(29, array('handle', 'result')),
        'GRAB_JOB_UNIQ' => array(30, array()),
        'JOB_ASSIGN_UNIQ' => array(31, array('handle', 'func', 'uniq', 'arg')),
        'SUBMIT_JOB_HIGH_BG' => array(32, array('func', 'uniq', 'arg')),
        'SUBMIT_JOB_LOW' => array(33, array('func', 'uniq', 'arg')),
        'SUBMIT_JOB_LOW_BG' => array(34, array('func', 'uniq', 'arg')),
        'SUBMIT_JOB_SCHED' => array(35, array('func', 'uniq', 'minute', 'hour', 'dayofmonth', 'month', 'dayofmonth', 'arg')),
        'SUBMIT_JOB_EPOCH' => array(36, array('func', 'uniq', 'epoch', 'arg'))
    );

    /**
     * The reverse of GearmanPHP_Base_Connection::$commands
     *
     * This is the same as the Net_Gearman_Connection::$commands array only
     * it's keyed by the magic (integer value) value of the command.
     *
     * @var array $magic
     * @see GearmanPHP_Base_Connection::$commands
     * @see GearmanPHP_Base_Connection::connect()
     */
    static protected $magic = array();

    /**
     * Tasks waiting for a handle
     *
     * Tasks are popped onto this queue as they're submitted so that they can
     * later be popped off of the queue once a handle has been assigned via
     * the job_created command.
     *
     * @access      public
     * @var         array           $waiting
     * @static
     */
    static public $waiting = array();

    /**
     * Is PHP's multibyte overload turned on?
     *
     * @var integer $multiByteSupport
     */
    static protected $multiByteSupport = null;

    static protected $errorCallbacks = array();

    /**
     * Constructor
     *
     * @return void
     */
    final private function __construct()
    {
        // Don't allow this class to be instantiated
    }

    /**
     * Connect to Gearman
     *
     * Opens the socket to the Gearman Job server. It throws an exception if
     * a socket error occurs. Also populates Net_Gearman_Connection::$magic.
     *
     * @param string $host    e.g. 127.0.0.1 or 127.0.0.1:4730
     * @param int    $timeout Timeout in milliseconds
     *
     * @return resource A connection to a Gearman server
     * @throws GearmanPHP_Base_Exception when it can't connect to server
     */
    static public function connect($host, $timeout = 2000)
    {
        if (!count(self::$magic)) {
            foreach (self::$commands as $cmd => $i) {
                self::$magic[$i[0]] = array($cmd, $i[1]);
            }
        }

        $err   = '';
        $errno = 0;
        $port  = 4730;

        if (strpos($host, ':')) {
            list($host, $port) = explode(':', $host);
        }

        $start = microtime(true);
        do {
            $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
            @socket_connect($socket, $host, $port);
            $errorCode = socket_last_error($socket);

            socket_set_nonblock($socket);
            socket_set_option($socket, SOL_TCP, 1, 1);
            $timeLeft = ((microtime(true) - $start) * 1000);
        } while (!is_resource($socket) && $timeLeft < $timeout);

        if ($errorCode == 111) {
            throw new GearmanPHP_Base_Exception("Can't connect to server");
        }

        self::$waiting[(int)$socket] = array();
        return $socket;
    }

    /**
     * Send a command to Gearman
     *
     * This is the command that takes the string version of the command you
     * wish to run (e.g. 'can_do', 'grab_job', etc.) along with an array of
     * parameters (in key value pairings) and packs it all up to send across
     * the socket.
     *
     * @param resource $socket  The socket to send the command to
     * @param string   $command Command to send (e.g. 'can_do')
     * @param array    $params  Params to send
     *
     * @return boolean
     * @throws GearmanPHP_Base_Exception on invalid command or unable to write
     */
    static public function sendCommand($socket, $command, array $params = array())
    {
        if (!isset(self::$commands[$command])) {
            throw new GearmanPHP_Base_Exception('Invalid command: ' . $command);
        }

        $data = array();
        foreach (self::$commands[$command][1] as $field) {
            if (isset($params[$field])) {
                $data[] = $params[$field];
            }
        }

        $d = implode("\x00", $data);

        $cmd = "\0REQ" . pack("NN",
                self::$commands[$command][0],
                self::stringLength($d)) . $d;

        $cmdLength = self::stringLength($cmd);
        $written = 0;
        $error = false;

        do {
            $check = socket_write($socket,
                    self::subString($cmd, $written, $cmdLength),
                    $cmdLength);

            if ($check === false) {
                if ((defined('SOCKET_EAGAIN') && socket_last_error($socket) == SOCKET_EAGAIN) ||
                        (defined('SOCKET_EWOULDBLOCK') && socket_last_error($socket) == SOCKET_EWOULDBLOCK) ||
                        (defined('SOCKET_EINPROGRESS') && socket_last_error($socket) == SOCKET_EINPROGRESS)) {
                    // skip this is okay
                }
                else {
                    $error = true;
                    break;
                }
            }

            $written += (int)$check;
        } while ($written < $cmdLength);

        if ($error === true) {
            throw new GearmanPHP_Base_Exception(
            'Could not write command to socket'
            );
        }
    }

    static public function getResponse($socket)
    {
        $responseData = array();
        do  {
            $responseData = self::read($socket);
        } while ($responseData === array());
        return $responseData;
    }

    /**
     * Read command from Gearman
     *
     * @param resource $socket The socket to read from
     *
     * @see GearmanPHP_Base_Connection::$magic
     * @return array Result read back from Gearman
     * @throws GearmanPHP_Base_Exception connection issues or invalid responses
     */
    static public function read($socket)
    {
        $header = '';
        do {
            $buf = socket_read($socket, 12 - self::stringLength($header));
            $header .= $buf;
        } while ($buf !== false &&
                $buf !== '' && self::stringLength($header) < 12);

        if ($buf === '') {
            throw new GearmanPHP_Base_Exception("Connection was reset");
        }

        if (self::stringLength($header) == 0) {
            return array();
        }
        $resp = @unpack('a4magic/Ntype/Nlen', $header);

        if (!count($resp) == 3) {
            throw new GearmanPHP_Base_Exception('Received an invalid response');
        }

        if (!isset(self::$magic[$resp['type']])) {
            throw new GearmanPHP_Base_Exception(
            'Invalid response magic returned: ' . $resp['type']
            );
        }

        $return = array();
        if ($resp['len'] > 0) {
            $data = '';
            while (self::stringLength($data) < $resp['len']) {
                $data .= socket_read($socket, $resp['len'] - self::stringLength($data));
            }

            $d = explode("\x00", $data, count(self::$magic[$resp['type']][1]));
            foreach (self::$magic[$resp['type']][1] as $i => $a) {
                $return[$a] = $d[$i];
            }
        }

        $function = self::$magic[$resp['type']][0];
        if ($function == 'ERROR') {
            if (!self::stringLength($return['err_text'])) {
                $return['err_text'] = 'Unknown error; see error code.';
            }

            if (count(self::$errorCallbacks) > 0) {
                foreach(self::$errorCallbacks as $callback) {
                    call_user_func($callback, $return['err_text'], $return['err_code']);
                }
            } else {
                throw new GearmanPHP_Base_Exception(
                    $return['err_text'], $return['err_code']);
            }
        }

        return array('function' => self::$magic[$resp['type']][0],
                'type' => $resp['type'],
                'data' => $return);

    }

    /**
     * Blocking socket read
     *
     * @param resource $socket  The socket to read from
     * @param float    $timeout The timeout for the read
     *
     * @throws GearmanPHP_Base_Exception on timeouts
     * @return array
     */
    static public function blockingRead($socket, $timeout = 500)
    {
        static $cmds = array();

        $tv_sec  = floor(($timeout % 1000));
        $tv_usec = ($timeout * 1000);

        $start = microtime(true);
        while (count($cmds) == 0) {
            if (((microtime(true) - $start) * 1000) > $timeout) {
                throw new GearmanPHP_Base_Exception('Blocking read timed out');
            }

            $write  = null;
            $except = null;
            $read   = array($socket);

            socket_select($read, $write, $except, $tv_sec, $tv_usec);
            foreach ($read as $s) {
                $cmds[] = self::read($s);
            }
        }

        return array_shift($cmds);
    }

    /**
     * Close the connection
     *
     * @param resource $socket The connection/socket to close
     *
     * @return void
     */
    static public function close($socket)
    {
        if (is_resource($socket)) {
            socket_close($socket);
        }
    }

    /**
     * Are we connected?
     *
     * @param resource $conn The connection/socket to check
     *
     * @return boolean False if we aren't connected
     */
    static public function isConnected($conn)
    {
        return (is_null($conn) !== true &&
                        is_resource($conn) === true &&
                        strtolower(get_resource_type($conn)) == 'socket');
    }

    /**
     * Determine if we should use mb_strlen or stock strlen
     *
     * @param string $value The string value to check
     *
     * @return integer Size of string
     */
    static public function stringLength($value)
    {
        if (is_null(self::$multiByteSupport)) {
            self::$multiByteSupport = intval(ini_get('mbstring.func_overload'));
        }

        if (self::$multiByteSupport & 2) {
            return mb_strlen($value, '8bit');
        } else {
            return strlen($value);
        }
    }

    /**
     * Multibyte substr() implementation
     *
     * @param string  $str    The string to substr()
     * @param integer $start  The first position used
     * @param integer $length The maximum length of the returned string
     *
     * @return string Portion of $str specified by $start and $length
     */
    static public function subString($str, $start, $length)
    {
        if (is_null(self::$multiByteSupport)) {
            self::$multiByteSupport = intval(ini_get('mbstring.func_overload'));
        }

        if (self::$multiByteSupport & 2) {
            return mb_substr($str, $start, $length, '8bit');
        } else {
            return substr($str, $start, $length);
        }
    }

    static public function addErrorCallback(array $callback = array())
    {
        if(!empty ($callback)) {
            self::$errorCallbacks[] = $callback;
        }
    }
}