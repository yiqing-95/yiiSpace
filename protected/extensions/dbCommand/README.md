database-command
================

Yii command to create database migrations from existing schema. 
Migration classes are created in application `runtime` folder.

Features
--------

* primary key generation
* foreign key generation (skipped for SQLite)
* complete data and schmema dump

Download
--------

[Get it from github](https://github.com/schmunk42/database-command/tags) and place it into your application.

Usage
-----

Run the command...

    ./yiic database dump [<name>] [--prefix=<table_prefix>] [--createSchema=<0|1>] [--insertData=<0|1>] [--dbConnection=<db>]


Param            | Default | Info
-----------------|---------|-----------------------------------
name             | dump    | migration class name 
--prefix         |         | dump only tables with given prefix<br/>(specify multiple prefixes separated by commas)
--createSchema   | 1       | wheter to create tables 
--insertData     | 1       | wheter to create insert statements
--dbConnection   | db      | application component to use

#### Example

To create a migration from an existing application scheme, define an alternative database component in your 
application, e.g. `db-production`. The following command dumps all tables starting with `p3_media` and omits
the schema create statements.

    ./yiic database dump p3media-no-schema-production --prefix=p3_media --createSchema=0 --dbConnection=db-production


Requirements
------------

 * Yii 1.1.*

Configuration
-------------

`config/console.php`

    'commandMap' => array(
        'database' => array(
            'class' => 'vendor.schmunk42.database-command.EDatabaseCommand',
        ),
    )

Resources
---------

* Fork on [github](https://github.com/schmunk42/database-command)
* View at [Yii Extensions](http://www.yiiframework.com/extension/database-command/)
* [CHANGELOG](https://github.com/schmunk42/database-command/blob/master/CHANGELOG.md)
* [Phundament 3](http://phundament.com) Package