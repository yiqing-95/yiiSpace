<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="language" content="en" />

        <!-- blueprint CSS framework -->
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
        <!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->

        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/wizard.css" />

        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    </head>

    <body>
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
        <div class="container" id="page">
            <div id="header">
                <br/>
                <br/>
            </div><!-- header -->
            <div><?php echo CHtml::image(Yii::app()->request->baseUrl . '/images/bugitor_64.png',
                    'Bugitor - The Yii-powered issue tracker', array(
                        'title' => 'Bugitor - The Yii-powered issue tracker')) ?><font style="font-size: 2em; position: relative; bottom: 20px;" class="quiet">Bugitor Installer/Upgrader</font></div>
            <hr/>
            <?php echo CHtml::link('reset',array('logout'),array()); ?>
            <div id="content">
                <?php echo $content; ?>
            </div><!-- content -->
            <br/>
            <div class="span-24 alt"><div align="center" class="quiet">
                    <hr/>
                    Powered by <a class="noicon" title="Bugitor - The Yii-powered issue tracker" href="http://bitbucket.org/jacmoe/bugitor">Bugitor</a> &copy; 2010 - 2011 by Bugitor Team.<br/>
                    <a class="noicon" href="http://www.yiiframework.com/" rel="external"><img src="<?php echo Yii::app()->request->baseUrl ?>/images/yii_power_lightblue_white.gif" alt="Made with Yii Framework" title="Made with Yii Framework"/></a>
                    <hr/>
                </div>
            </div><!-- page -->
    </body>
</html>
