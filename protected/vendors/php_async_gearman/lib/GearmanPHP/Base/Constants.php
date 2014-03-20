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

/**
 * Gearman return types
 * (gearman_return_t)
 */
define('GEARMAN_SUCCESS',0);
define('GEARMAN_IO_WAIT',1);
define('GEARMAN_SHUTDOWN',2);
define('GEARMAN_SHUTDOWN_GRACEFUL',3);
define('GEARMAN_ERRNO',4);
define('GEARMAN_EVENT',5);
define('GEARMAN_TOO_MANY_ARGS',6);
define('GEARMAN_NO_ACTIVE_FDS',7);
define('GEARMAN_INVALID_MAGIC',8);
define('GEARMAN_INVALID_COMMAND',9);
define('GEARMAN_INVALID_PACKET',10);
define('GEARMAN_UNEXPECTED_PACKET',11);
define('GEARMAN_GETADDRINFO',12);
define('GEARMAN_NO_SERVERS',13);
define('GEARMAN_LOST_CONNECTION',14);
define('GEARMAN_MEMORY_ALLOCATION_FAILURE',15);
define('GEARMAN_JOB_EXISTS',16);
define('GEARMAN_JOB_QUEUE_FULL',17);
define('GEARMAN_SERVER_ERROR',18);
define('GEARMAN_WORK_ERROR',19);
define('GEARMAN_WORK_DATA',20);
define('GEARMAN_WORK_WARNING',21);
define('GEARMAN_WORK_STATUS',22);
define('GEARMAN_WORK_EXCEPTION',23);
define('GEARMAN_WORK_FAIL',24);
define('GEARMAN_NOT_CONNECTED',25);
define('GEARMAN_COULD_NOT_CONNECT',26);
define('GEARMAN_SEND_IN_PROGRESS',27);
define('GEARMAN_RECV_IN_PROGRESS',28);
define('GEARMAN_NOT_FLUSHING',29);
define('GEARMAN_DATA_TOO_LARGE',30);
define('GEARMAN_INVALID_FUNCTION_NAME',31);
define('GEARMAN_INVALID_WORKER_FUNCTION',32);
define('GEARMAN_NO_REGISTERED_FUNCTIONS',33);
define('GEARMAN_NO_JOBS',34);
define('GEARMAN_ECHO_DATA_CORRUPTION',35);
define('GEARMAN_NEED_WORKLOAD_FN',36);
define('GEARMAN_PAUSE',37);
define('GEARMAN_UNKNOWN_STATE',38);
define('GEARMAN_PTHREAD',39);
define('GEARMAN_PIPE_EOF',40);
define('GEARMAN_QUEUE_ERROR',41);
define('GEARMAN_FLUSH_DATA',42);
define('GEARMAN_SEND_BUFFER_TOO_SMALL',43);
define('GEARMAN_IGNORE_PACKET',44);
define('GEARMAN_UNKNOWN_OPTION',45);
define('GEARMAN_TIMEOUT',46);
define('GEARMAN_MAX_RETURN',47);

/**
 * Gearman Worker Options
 * (gearman_worker_st)
 */
define('GEARMAN_WORKER_ALLOCATED',0);
define('GEARMAN_WORKER_NON_BLOCKING',1);
define('GEARMAN_WORKER_PACKET_INIT',2);
define('GEARMAN_WORKER_GRAB_JOB_IN_USE',3);
define('GEARMAN_WORKER_PRE_SLEEP_IN_USE',4);
define('GEARMAN_WORKER_WORK_JOB_IN_USE',5);
define('GEARMAN_WORKER_CHANGE',6);
define('GEARMAN_WORKER_GRAB_UNIQ',7);
define('GEARMAN_WORKER_TIMEOUT_RETURN',8);

/**
 * Gearman Client Options
 * (gearman_client_st)
 */
define('GEARMAN_CLIENT_ALLOCATED',0);
define('GEARMAN_CLIENT_NON_BLOCKING',1);
define('GEARMAN_CLIENT_TASK_IN_USE',2);
define('GEARMAN_CLIENT_UNBUFFERED_RESULT',3);
define('GEARMAN_CLIENT_NO_NEW',4);
define('GEARMAN_CLIENT_FREE_TASKS',5);