<?php
//define URL adress
define("URL","http://yoururladress");

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

//db type 0 - MySQL/MariaDB, 1- PostgreSQL
define("DBTYPE",0);

//if db type 1
define("PGSQLSCHEMA",""); //if PostreSQL schema name other than public

//db info
define("HOST",""); //if empty the database connection is disabled
define("DBNAME","");
define("PORT",""); //if empty port is default
define("DBUSER","");
define("DBPASS","");


?>