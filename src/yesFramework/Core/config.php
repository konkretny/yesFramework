<?php

declare(strict_types=1);

// Environment variables are now used for configuration.
// Default fallback values are provided if the `.env` file is missing.

define("URL", $_ENV['APP_URL'] ?? "http://yoururladress");
define("LANGUAGE", $_ENV['APP_LANG'] ?? "en_EN");

date_default_timezone_set($_ENV['APP_TIMEZONE'] ?? 'Europe/Warsaw');

define("MAIN_EMAIL", $_ENV['MAIN_EMAIL'] ?? "Your e-mail adress");
define("NAME", $_ENV['APP_NAME'] ?? "Name your website");

if (($_ENV['APP_ENV'] ?? 'development') === 'development') {
    error_reporting(-1);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}

// Database configuration
define("DBTYPE", (int)($_ENV['DB_TYPE'] ?? 0));
define("PGSQLSCHEMA", $_ENV['DB_PGSQL_SCHEMA'] ?? "");

define("HOST", $_ENV['DB_HOST'] ?? "");
define("DBNAME", $_ENV['DB_NAME'] ?? "");
define("PORT", $_ENV['DB_PORT'] ?? "");
define("DBUSER", $_ENV['DB_USER'] ?? "");
define("DBPASS", $_ENV['DB_PASS'] ?? "");

define("DB_SQLITE_FILE_PATH", $_ENV['DB_SQLITE_PATH'] ?? "");