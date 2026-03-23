<?php
require_once __DIR__ . '/configdb.php';

$configuracion = configdb_obtener_configuracion();

if (!defined('APP_DB_HOST'))
  define('APP_DB_HOST', $configuracion['host']);

if (!defined('APP_DB_PORT'))
  define('APP_DB_PORT', $configuracion['port']);

if (!defined('APP_DB_NAME'))
  define('APP_DB_NAME', $configuracion['dbname']);

if (!defined('APP_DB_USER'))
  define('APP_DB_USER', $configuracion['user']);

if (!defined('APP_DB_PASSWORD'))
  define('APP_DB_PASSWORD', $configuracion['password']);

if (!defined('APP_DB_CHARSET'))
  define('APP_DB_CHARSET', $configuracion['charset']);

if (!defined('APP_LOG_RUTA'))
  define('APP_LOG_RUTA', $configuracion['ruta_logs']);
?>
