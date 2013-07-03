<?php

// change the following paths if necessary
// 我的yii框架在PEAR 目录php bin的PEAR目录下 所以根据情况修改这里
$yiit= 'yii/framework/yiit.php';
$config=dirname(__FILE__).'/../config/test.php';

require_once($yiit);
require_once(dirname(__FILE__).'/WebTestCase.php');

Yii::createWebApplication($config);
