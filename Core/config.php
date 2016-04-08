<?php
//define URL adress
define("URL","http://yoururladress");

//define language
define("LANGUAGE","en_EN");

//timezones
date_default_timezone_set('Europe/Warsaw');

//default controller
define("CONTROLLER","welcome.php");

//main e-mail
define("MAIN_EMAIL","Your e-mail adress");

//website name
define("NAME","Name your website");

//error reporting 0 -off, -1 -on
error_reporting(-1);

//db type 0 - MySQL/MariaDB, 1- PostgreSQL, 2- SQLite
define("DBTYPE",0);

//IF DBTYPE 1
define("PGSQLSCHEMA",""); //if PostreSQL schema name other than public


//db info - EDIT IF YOUR DBTYPE IS 0 OR 1
define("HOST",""); //if empty the database connection is disabled
define("DBNAME","");
define("PORT",""); //if empty port is default
define("DBUSER","");
define("DBPASS","");

//dbinfo - EDIT IF YOUR DBTYPE IS 2
define("DB_SQLITE_FILE_PATH",""); //your database path with name - SQLite


?>