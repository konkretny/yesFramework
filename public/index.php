<?php

declare(strict_types=1);
/*
Author: Marcin Romanowicz
URL: http://yesframework.com/
License: MIT
Version 3.0.2
*/

//check PHP Version
if (PHP_VERSION_ID < 70000) {
    echo 'Your version of PHP is ' . phpversion() . '. Required min. 7.0.0';
    exit;
}

// error handler function
function yesErrorHandler($errno, $errstr, $errfile, $errline)
{
    if (!(error_reporting() & $errno)) {
        return;
    }

    switch ($errno) {
        case E_USER_ERROR:
            echo "<b>My ERROR</b> [$errno] $errstr<br />\n";
            echo "  Fatal error on line $errline in file $errfile";
            echo ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
            echo "Aborting...<br />\n";
            exit(1);
            break;

        case E_USER_WARNING:
            echo "<b>My WARNING</b> [$errno] $errstr<br />\n";
            break;

        case E_USER_NOTICE:
            echo "<b>My NOTICE</b> [$errno] $errstr<br />\n";
            break;

        default:
            echo "Unknown error type: [$errno] $errstr<br />\n";
            break;
    }

    /* Don't execute PHP internal error handler */
    return true;
}
//set_error_handler("yesErrorHandler");


//start session
session_start();


//autloader
function ClassLoader($className)
{
    $className = (string) str_replace('\\', DIRECTORY_SEPARATOR, $className);
    require_once(__DIR__ . '/../src/' . $className . '.php');
    return true;
}
spl_autoload_register('ClassLoader');

//autoload composer
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once(__DIR__ . '/../vendor/autoload.php');
}

//load helpers
require_once(__DIR__ . '/../src/yesFramework/Core/helper.php');

//load config
require_once(__DIR__ . '/../src/yesFramework/Core/config.php');

//PDO connect
if (strlen(DBNAME) > 0 || strlen(DB_SQLITE_FILE_PATH) > 0) {
    if (PORT == '') {
        $port_nr = '';
    } else {
        $port_nr = ';port=' . PORT;
    }
    if (DBTYPE == 0) {
        $database_type = 'mysql:';
    } elseif (DBTYPE == 1) {
        $database_type = 'pgsql:';
    } elseif (DBTYPE == 2) {
        $database_type = 'sqlite:';
    } else {
        echo 'error database type';
        exit;
    }

    try {
        if (DBTYPE == 2) {
            $PDO = new \PDO($database_type . DB_SQLITE_FILE_PATH);
        } else {
            $PDO = new \PDO($database_type . 'host=' . HOST . $port_nr . ';dbname=' . DBNAME, DBUSER, DBPASS, array(\PDO::ATTR_EMULATE_PREPARES => false, \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
            if (strlen(PGSQLSCHEMA) > 0) {
                $PDO->exec('SET search_path TO ' . PGSQLSCHEMA);
            }
        }
    } catch (PDOException $e) {
        echo 'Connection error';

        //uncomment if you need.
        //$e->getMessage();
    }
}

//AntiCSRF
if (!isset($_SESSION['csrf'])) {
    $csrf = hash('sha256', uniqid('yF', true) . rand(1, 1000));
    $_SESSION['csrf'] = $csrf;
}

//start
if (!isset($_GET['page'])) {
    require_once(__DIR__ . '/../src/yesFramework/App/Controllers/' . CONTROLLER);
} else {
    $dir = scandir(__DIR__ . '/../src/yesFramework/App/Controllers/');
    $dir_ok = 0;
    foreach ($dir as $check_dir) {
        $check_dir = str_replace('.php', '', $check_dir);
        if ($check_dir == $_GET['page']) {
            $dir_ok = $check_dir;
        }
    }
    if ($dir_ok != '0') {
        require_once(__DIR__ . '/../src/yesFramework/App/Controllers/' . $dir_ok . '.php');
    } else {
        http_response_code(404);
        redirect('EPages/404.html');
    }
}
