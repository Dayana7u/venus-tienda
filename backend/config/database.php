<?php
if (!defined('APP_DB_HOST'))
  define('APP_DB_HOST', 'localhost');

if (!defined('APP_DB_PORT'))
  define('APP_DB_PORT', '5432');

if (!defined('APP_DB_NAME'))
  define('APP_DB_NAME', 'tienda_virtual');

if (!defined('APP_DB_USER'))
  define('APP_DB_USER', 'postgres');

if (!defined('APP_DB_PASSWORD'))
  define('APP_DB_PASSWORD', 'postgres');

if (!defined('APP_DB_CHARSET'))
  define('APP_DB_CHARSET', 'UTF8');

if (!defined('APP_LOG_RUTA'))
  define('APP_LOG_RUTA', dirname(__DIR__).'/storage/logs/app.log');
?>
