<?php
require_once("ZabbixAPI.class.php");

// This enables debugging, this is rather verbose but can help debug problems
//ZabbixAPI::debugEnabled(TRUE);

// This logs into Zabbix, and returns false if it fails
ZabbixAPI::login('http://genbook/zabbix/','apiuser','ap1')
    or die('Unable to login: '.print_r(ZabbixAPI::getLastError(),true));

// This gets the version of the zabbix server
$version = ZabbixAPI::fetch_string('apiinfo','version')
    or die('Unable to get Zabbix Version: '.print_r(ZabbixAPI::getLastError(),true));
echo "Server running Zabbix API Version: $version\n<br>";

// Fetch the user ids on the server, fetch_column ensures we just get the first item
// if you want to understand why I do this, put fetch_array instead and see!
$users = ZabbixAPI::fetch_column('user','get')
    or die('Unable to get user ids: '.print_r(ZabbixAPI::getLastError(),true));
echo "User IDs found: ".implode($users,',')."\n<br>";

// Fetch hosts, but with extend option to get more data, and limit records returned
$five_hosts = ZabbixAPI::fetch_array('host','get',array('extendoutput'=>1, 'limit'=>5)) 
    or die('Unable to get hosts: '.print_r(ZabbixAPI::getLastError(),true));
echo "Retrieved maximum of five hosts: ".print_r($five_hosts, true)."\n<br>";

// Do a simple update of userid = 1, set refresh = 1000
// NOTE: If this fails, it's because your API user is not a super-admin
ZabbixAPI::query('user','update',array('userid'=>1, 'refresh'=>1000)) 
    or die('Unable to update: '.print_r(ZabbixAPI::getLastError(),true));
echo "Updated userid 1 with refresh value of 1000!\n";
