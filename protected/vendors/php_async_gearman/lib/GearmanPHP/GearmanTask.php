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
class GearmanPHP_GearmanTask
{
    /* Magic functions for internal var access */
    protected $_data = array();

    public function __set($name, $value) {
        $this->_data[$name] = $value;
    }

    public function __get($name) {
        if (array_key_exists($name, $this->_data)) {
            return $this->_data[$name];
        }
        return null;
    }

    public function __isset($name) {
        return isset($this->_data[$name]);
    }

    public function __unset($name) {
        unset($this->_data[$name]);
    }
    /* End of magic functions for internal var access */


    /**
     * Create a GearmanTask instance
     *
     * @return GearmanPHP_GearmanTask
     */
    public function __construct()
    {}

    public function  __call($name,  $arguments)
    {
        //catch for methods which named like reserved words in php
        if($name === 'function') {
            return $this->_gmtFunction();
        } else {
            $msg = 'function not available.';
            throw new GearmanPHP_Base_Exception($msg);
        }
    }

    /**
     * Create a task (deprecated)
     *
     * @return GearmanPHP_GearmanTask|bool A GearmanTask oject or FALSE on failure.
     */
    public function create()
    {
        trigger_error("Deprecated function (" . __CLASS__ . "::" . __METHOD__ . ") called.", E_USER_NOTICE);
        if(is_object($this)) {
            return $this;
        } else {
            return false;
        }
    }

    /**
     * Get data returned for a task
     *
     * @return string|bool the serialized data, or FALSE if no data is present.
     */
    public function data()
    {
        if (is_null($this->result)) {
            return false;
        } else {
            return $this->result;
        }
    }

    /**
     * Get the size of returned data
     *
     * @return int|bool The data size, or FALSE if there is no data.
     */
    public function dataSize()
    {
        return GearmanPHP_Base_Common::stringLength($this->result);
    }

    /**
     * Get associated function name (deprecated)
     *
     * @return string A function name.
     */
    protected function _gmtFunction()
    {
        trigger_error("Deprecated function (" . __CLASS__ . "::" . __METHOD__ . ") called.", E_USER_NOTICE);
        return $this->functionName();
    }

    /**
     * Get associated function name
     *
     * @return string A function name.
     */
    public function functionName()
    {
        return $this->functionName;
    }

    /**
     * Determine if task is known
     *
     * @return bool TRUE if the task is known, FALSE otherwise.
     */
    public function isKnown()
    {
        if(empty($this->known)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Test whether the task is currently running
     *
     * @return bool TRUE if the task is running, FALSE otherwise.
     */
    public function isRunning()
    {
        if(empty($this->running)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Returns the job handle for this task.
     *
     * @return string The opaque job handle.
     */
    public function jobHandle()
    {
        return $this->handle;
    }

    /**
     * Read work or result data into a buffer for a task
     *
     * @param int $data_len Length of data to be read.
     * @return array|bool An array whose first element is the length of data read and the second is the data buffer. Returns FALSE if the read failed.
     */
    public function recvData($data_len)
    {

    }

    /**
     * Get the last return code
     *
     * @return int A valid Gearman return code.
     */
    public function returnCode()
    {
        return $this->lastReturnCode;
    }

    /**
     * Send data for a task (deprecated)
     *
     * @param string $data Data to send to the worker.
     * @return int The length of data sent, or FALSE if the send failed.
     */
    public function sendData($data)
    {
        trigger_error("Deprecated function (" . __CLASS__ . "::" . __METHOD__ . ") called.", E_USER_NOTICE);
    }

    /**
     * Send data for a task
     *
     * @param string $data Data to send to the worker.
     * @return int The length of data sent, or FALSE if the send failed.
     */
    public function sendWorkload($data)
    {
        $this->workload = $data;
    }

    /**
     * Get completion percentage denominator
     *
     * @return int|bool A number between 0 and 100, or FALSE if cannot be determined.
     */
    public function taskDenominator()
    {
        if(is_null($this->denominator)) {
            return false;
        } else {
            return $this->denominator;
        }
    }

    /**
     * Get completion percentage numerator
     *
     * @return int|bool A number between 0 and 100, or FALSE if cannot be determined.
     */
    public function taskNumerator()
    {
        if(is_null($this->numerator)) {
            return false;
        } else {
            return $this->numerator;
        }
    }

    /**
     * Get the unique identifier for a task
     *
     * @return string The unique identifier, or FALSE if no identifier is assigned.
     */
    public function unique()
    {
        return $this->uniqueId;
    }

    /**
     * Get the unique identifier for a task (deprecated)
     *
     * @return string The unique identifier, or FALSE if no identifier is assigned.
     */
    public function uuid()
    {
        trigger_error("Deprecated function (" . __CLASS__ . "::" . __METHOD__ . ") called.", E_USER_NOTICE);
        return $this->unique();
    }
}