<?php
/**
 * Configuración local de PostgreSQL para el proyecto tienda_virtual.
 *
 * @return     array   Arreglo con la configuración de conexión.
 */
function configdb_obtener_configuracion() {
  return [
    'driver'   => getenv('DB_DRIVER') !== false ? getenv('DB_DRIVER') : 'pgsql',
    'host'     => getenv('DB_HOST') !== false ? getenv('DB_HOST') : 'localhost',
    'port'     => getenv('DB_PORT') !== false ? getenv('DB_PORT') : '5432',
    'dbname'   => getenv('DB_NAME') !== false ? getenv('DB_NAME') : 'tienda_virtual',
    'user'     => getenv('DB_USER') !== false ? getenv('DB_USER') : 'postgres',
    'password' => getenv('DB_PASSWORD') !== false ? getenv('DB_PASSWORD') : 'postgres',
    'charset'  => 'utf8',
    'opciones' => [
      PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES   => false,
    ],
    'ruta_logs' => __DIR__ . '/../storage/logs/app.log',
  ];
}
/**
 * Método encargado de construir el DSN de conexión.
 *
 * @param      array  $configuracion  Configuración de la conexión.
 *
 * @return     string  Cadena DSN para PDO.
 */
function configdb_obtener_dsn($configuracion) {
  return sprintf(
    '%s:host=%s;port=%s;dbname=%s',
    $configuracion['driver'],
    $configuracion['host'],
    $configuracion['port'],
    $configuracion['dbname']
  );
}
/**
 * Método encargado de obtener la conexión PDO.
 *
 * @return     PDO  Conexión activa a PostgreSQL.
 */
function configdb_obtener_conexion() {
  $configuracion = configdb_obtener_configuracion();
  $dsn           = configdb_obtener_dsn($configuracion);

  return new PDO(
    $dsn,
    $configuracion['user'],
    $configuracion['password'],
    $configuracion['opciones']
  );
}
/**
 * Método encargado de registrar logs técnicos en archivo.
 *
 * @param      string  $modulo    Ruta del archivo que genera el log.
 * @param      string  $funcion   Nombre de la función que genera el log.
 * @param      string  $mensaje   Mensaje principal del log.
 * @param      string  $detalle   Detalle adicional del log.
 */
function configdb_registrar_log($modulo, $funcion, $mensaje, $detalle = '') {
  $configuracion = configdb_obtener_configuracion();
  $ruta_logs     = $configuracion['ruta_logs'];
  $directorio    = dirname($ruta_logs);
  $contenido     = sprintf(
    "[%s] [%s] [%s] %s %s%s",
    date('Y-m-d H:i:s'),
    $modulo,
    $funcion,
    $mensaje,
    $detalle,
    PHP_EOL
  );

  if (!is_dir($directorio))
    mkdir($directorio, 0777, true);

  error_log($contenido, 3, $ruta_logs);
}
?>
