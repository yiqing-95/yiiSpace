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
class GearmanPHP_GearmanJob
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
     * Create a GearmanJob instance
     *
     * @return GearmanPHP_GearmanJob
     */
    public function __construct()
    {}

    /**
     * Send the result and complete status (deprecated)
     *
     * @param string $result Serialized result data.
     * @return bool Returns TRUE on success or FALSE on failure.
     */
    public function complete($result)
    {
        trigger_error("Deprecated function (" . __CLASS__ . "::" . __METHOD__ . ") called.", E_USER_NOTICE);
        return $this->sendComplete($result);
    }

    /**
     * Send data for a running job (deprecated)
     *
     * @param string $data Arbitrary serialized data.
     * @return bool Returns TRUE on success or FALSE on failure.
     */
    public function data($data)
    {
        trigger_error("Deprecated function (" . __CLASS__ . "::" . __METHOD__ . ") called.", E_USER_NOTICE);
        return $this->sendData($data);
    }

    /**
     * Send exception for running job (deprecated)
     *
     * @param string $exception An exception description.
     * @return bool Returns TRUE on success or FALSE on failure.
     */
    public function exception($exception)
    {
        trigger_error("Deprecated function (" . __CLASS__ . "::" . __METHOD__ . ") called.", E_USER_NOTICE);
        return $this->sendException($exception);
    }

    /**
     * Send fail status (deprecated)
     *
     * @return bool Returns TRUE on success or FALSE on failure.
     */
    public function fail()
    {
        trigger_error("Deprecated function (" . __CLASS__ . "::" . __METHOD__ . ") called.", E_USER_NOTICE);
        return $this->sendFail();
    }

    /**
     * Get function name
     *
     * @return string The name of a function.
     */
    public function functionName()
    {
        return $this->functionName;
    }

    /**
     * Get the job handle
     *
     * @return string An opaque job handle.
     */
    public function handle()
    {
        return $this->handle;
    }

    /**
     * Get last return code
     *
     * @return string A valid Gearman return code.
     */
    public function returnCode()
    {
        return $this->returnCode;
    }

    /**
     * Send the result and complete status
     *
     * @param string $result Serialized result data.
     * @return bool Returns TRUE on success or FALSE on failure.
     */
    public function sendComplete($result)
    {
        $this->setReturn('GEARMAN_SUCCESS');
        try {
            GearmanPHP_Base_Common::sendCommand($this->socket, 'WORK_COMPLETE', array(
                'handle' => $this->handle,
                'result' => json_encode($result)
            ));
        } catch (GearmanPHP_Base_Exception $e) {
            return false;
        }
        return true;
    }

    /**
     * Send data for a running job
     *
     * @param string $data Arbitrary serialized data.
     * @return bool Returns TRUE on success or FALSE on failure.
     */
    public function sendData($data)
    {
        $this->setReturn('GEARMAN_WORK_DATA');
        try {
            GearmanPHP_Base_Common::sendCommand($this->socket, 'WORK_DATA', array(
                'handle' => $this->handle,
                'result' => json_encode($data)
            ));
        } catch (GearmanPHP_Base_Exception $e) {
            return false;
        }
        return true;
    }

    /**
     * Send exception for running job (exception)
     *
     * @param string $exception An exception description.
     * @return bool Returns TRUE on success or FALSE on failure.
     */
    public function sendException($exception)
    {
        $this->setReturn('GEARMAN_WORK_EXCEPTION');
        try {
            GearmanPHP_Base_Common::sendCommand($this->socket, 'WORK_EXCEPTION', array(
                'handle' => $this->handle,
                'result' => json_encode($exception)
            ));
        } catch (GearmanPHP_Base_Exception $e) {
            return false;
        }
        return true;
    }

    /**
     * Send fail status
     *
     * @return bool Returns TRUE on success or FALSE on failure.
     */
    public function sendFail()
    {
        $this->setReturn('GEARMAN_WORK_FAIL');
        try {
            GearmanPHP_Base_Common::sendCommand($this->socket, 'WORK_FAIL', array(
                'handle' => $this->handle
            ));
        } catch (GearmanPHP_Base_Exception $e) {
            return false;
        }
        return true;
    }

    /**
     * Sends status information to the job server and any listening clients.
     * Use this to specify what percentage of the job has been completed.
     *
     * @param int $numerator The numerator of the precentage completed expressed as a fraction.
     * @param int $denominator The denominator of the precentage completed expressed as a fraction.
     * @return bool Returns TRUE on success or FALSE on failure.
     */
    public function sendStatus($numerator, $denominator)
    {
        $this->setReturn('GEARMAN_WORK_STATUS');
        try {
            GearmanPHP_Base_Common::sendCommand($this->socket, 'WORK_STATUS', array(
                'handle' => $this->handle,
                'numerator' => $numerator,
                'denominator' => $denominator
            ));
        } catch (GearmanPHP_Base_Exception $e) {
            return false;
        }
        return true;
    }

    /**
     * Sends a warning for this job while it is running.
     *
     * @param string $warning A warning messages.
     * @return bool Returns TRUE on success or FALSE on failure.
     */
    public function sendWarning($warning)
    {
        $this->setReturn('GEARMAN_WORK_WARNING');
        try {
            GearmanPHP_Base_Common::sendCommand($this->socket, 'WORK_WARNING', array(
                'handle' => $this->handle,
                'result' => json_encode($warning)
            ));
        } catch (GearmanPHP_Base_Exception $e) {
            return false;
        }
        return true;
    }

    /**
     * Sets the return value for this job, indicates how the job completed.
     *
     * @param string $gearman_return_t A valid Gearman return value.
     * @return bool Always returns TRUE.
     */
    public function setReturn($gearman_return_t)
    {
        $this->returnCode = $gearman_return_t;
        return true;
    }

    /**
     * Send status (deprecated)
     *
     * @param int $numerator The numerator of the precentage completed expressed as a fraction.
     * @param int $denominator The denominator of the precentage completed expressed as a fraction.
     * @return bool Returns TRUE on success or FALSE on failure.
     */
    public function status($numerator, $denominator)
    {
        trigger_error("Deprecated function (" . __CLASS__ . "::" . __METHOD__ . ") called.", E_USER_NOTICE);
        return $this->sendStatus($numerator, $denominator);
    }

    /**
     * Get the unique identifier
     *
     * @return string An opaque unique identifier.
     */
    public function unique()
    {
        return $this->uuid;
    }

    /**
     * Send a warning (deprecated)
     *
     * @param string $warning A warning messages.
     * @return bool Returns TRUE on success or FALSE on failure.
     */
    public function warning($warning)
    {
        trigger_error("Deprecated function (" . __CLASS__ . "::" . __METHOD__ . ") called.", E_USER_NOTICE);
        return $this->sendWarning($warning);
    }

    /**
     * Get workload
     *
     * @return string Serialized data.
     */
    public function workload()
    {
        return $this->workload;
    }

    /**
     * Get size of work load
     *
     * @return int The size in bytes.
     */
    public function workloadSize()
    {
        return GearmanPHP_Base_Common::stringLength($this->workload);
    }
}