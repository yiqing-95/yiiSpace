<?php
require_once 'PHPAsyncClient.php';
date_default_timezone_set('Asia/Shanghai');

class AsyncTest {

    const
        LOG_FILE = '/debug.log';

    static public function run() {
        if (PHPAsyncClient::in_callback(__FILE__)) {
            self::log('php Async callback');
            PHPAsyncClient::parse();
            return;
        }
        if (PHPAsyncClient::is_main(__FILE__)) {
            self::log('main run');
            $async_call = PHPAsyncClient::getInstance();
            $async_call->AsyncCall('AsyncTest', 'callback', array(
                'content' => 'Hello World!!!',
            ), array(
                'class' => 'AsyncTest',
                'method' => 'callback',
                'params' => array(
                    'content' => 'Hello Callback!',
                ),
            ), __FILE__);
            return;
        }
    }

    static public function callback($args) {
        self::log('AsyncTest callback run');
        self::log('AsyncTest callback args:'.print_r($args, true));
    }

    static public function log($content) {
        $fullname = dirname(__FILE__).self::LOG_FILE;
        $content = date('[Y-m-d H:i:s]').$content."\n";
        file_put_contents($fullname, $content, FILE_APPEND);
    }
}

AsyncTest::run();