<?php
        /*
         * Simple 'user management'
         */
        define ('USERNAME', 'sysadmin');
        define ('PASSWORD', 'sysadmin');
        /*
         * Always announce XRDS OAuth discovery
         */
        #header('X-XRDS-Location: http://' . $_SERVER['SERVER_NAME'] . '/services.xrds');
        /*
         * Initialize the database connection
         */
		 /*
        $info = parse_url(DB_DSN);

        ($GLOBALS['db_conn'] = mysql_connect($info['host'], $info['user'], $info['pass'])) || die(mysql_error());
		var_dump($info);die;
        mysql_select_db(basename($info['path']), $GLOBALS['db_conn']) || die(mysql_error());
        unset($info);
        OAuthStore::instance('MySQL', array('conn' => $GLOBALS['db_conn']));
		*/