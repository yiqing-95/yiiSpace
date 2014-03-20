<?php

/*
 * This file is part of
 *     ____              _ __
 *    / __ )__  ______ _(_) /_____  _____
 *   / __  / / / / __ `/ / __/ __ \/ ___/
 *  / /_/ / /_/ / /_/ / / /_/ /_/ / /
 * /_____/\__,_/\__, /_/\__/\____/_/
 *             /____/
 * A Yii powered issue tracker
 * http://bitbucket.org/jacmoe/bugitor/
 *
 * Copyright (C) 2009 - 2013 Bugitor Team
 *
 * Permission is hereby granted, free of charge, to any person
 * obtaining a copy of this software and associated documentation files
 * (the "Software"), to deal in the Software without restriction,
 * including without limitation the rights to use, copy, modify, merge,
 * publish, distribute, sublicense, and/or sell copies of the Software,
 * and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 * The above copyright notice and this permission notice shall be included
 * in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
 * DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT
 * OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE
 * OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
?>
<?php

/**
 * ContactForm class.
 * ContactForm is the data structure for keeping
 * contact form data. It is used by the 'contact' action of 'SiteController'.
 */
class Connectiondetails extends CFormModel {

    public $host = 'localhost';
    public $dbname = 'bugitor';
    public $username;
    public $tablePrefix = 'bugitor_';
    public $password;
    public $charset = 'utf8';

    /**
     * Declares the validation rules.
     */
    public function rules() {
        return array(
            // name, email, subject and body are required
            array('host, 
                            dbname, 
                            username, 
                            tablePrefix,
                            password,
                            charset', 'required'),
        );
    }

    public function attributeLabels() {
        return array(
            'host' => 'MySQL server host name',
            'dbname' => 'Database name',
            'username' => 'Database username',
            'tablePrefix' => 'Table prefix',
            'charset' => 'Character set',
        );
    }

    public function getForm() {
        return new CForm(array(
            'showErrorSummary' => true,
            'elements' => array(
                'host' => array(
                    'hint' => '6-12 characters; letters, numbers, and underscore'
                ),
                'dbname' => array(
                    'hint' => '6-12 characters; letters, numbers, and underscore'
                ),
                'username' => array(
                    'hint' => '6-12 characters; letters, numbers, and underscore'
                ),
                'tablePrefix' => array(
                    'hint' => '6-12 characters; letters, numbers, and underscore'
                ),
                'password' => array(
                    'hint' => '6-12 characters; letters, numbers, and underscore'
                ),
                'charset' => array(
                    'hint' => '6-12 characters; letters, numbers, and underscore'
                ),
            ),
            'buttons' => array(
                'previous' => array(
                    'type' => 'submit',
                    'label' => 'Go Back'
                ),
                'submit' => array(
                    'type' => 'submit',
                    'label' => 'Next'
                ),
            )
                ), $this);
    }

    public function save() {
        $db_config = file_get_contents(dirname(__FILE__) . '/../../../protected/config/db.in.php');
        $db_config = str_replace('{host_in}', $this->host, $db_config);
        $db_config = str_replace('{dbname_in}', $this->dbname, $db_config);
        $db_config = str_replace('{username_in}', $this->username, $db_config);
        $db_config = str_replace('{tablePrefix_in}', $this->tablePrefix, $db_config);
        $db_config = str_replace('{password_in}', $this->password, $db_config);
        $db_config = str_replace('{charset_in}', $this->charset, $db_config);

        $fd = fopen(dirname(__FILE__) . '/../../../protected/config/db.php', "w");
        fwrite($fd, $db_config);
        fclose($fd);
        @chmod(dirname(__FILE__) . '/../../../protected/config/db.php', 0775);
        return true;
    }

}
