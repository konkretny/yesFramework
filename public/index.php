<?php

declare(strict_types=1);
/*
Author: Marcin Romanowicz
URL: http://yesframework.com/
License: MIT
Version 3.1.1
*/

//check PHP Version
if (PHP_VERSION_ID < 80400) {
    echo 'Your version of PHP is ' . phpversion() . '. Required min. 8.4.0';
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


//autoload composer
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once(__DIR__ . '/../vendor/autoload.php');
}

//load dotenv
use yesFramework\Core\Classess\DotEnv;
DotEnv::load(__DIR__ . '/../.env');

//load config
require_once(__DIR__ . '/../src/yesFramework/Core/config.php');

//PDO connect & DB Class setup
use yesFramework\Core\Classess\Db;

$dbInstance = null;
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
            $pdo = new \PDO($database_type . DB_SQLITE_FILE_PATH);
        } else {
            $pdo = new \PDO($database_type . 'host=' . HOST . $port_nr . ';dbname=' . DBNAME, DBUSER, DBPASS, array(\PDO::ATTR_EMULATE_PREPARES => false, \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
            if (strlen(PGSQLSCHEMA) > 0) {
                $pdo->exec('SET search_path TO ' . PGSQLSCHEMA);
            }
        }
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        $dbInstance = new Db($pdo, $database_type);

    } catch (\PDOException $e) {
        echo 'Database connection error';
        exit;
    }
}

//AntiCSRF
if (!isset($_SESSION['csrf'])) {
    $csrf = hash('sha256', uniqid('yF', true) . rand(1, 1000));
    $_SESSION['csrf'] = $csrf;
}

// Initialize Router
use yesFramework\Core\Classess\Router;
use yesFramework\App\Controllers\WelcomeController;

$router = new Router($dbInstance);

// Register routes
$router->get('/', [WelcomeController::class, 'index']);

// Resolve the request
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';

$router->resolve($requestMethod, $requestUri);
