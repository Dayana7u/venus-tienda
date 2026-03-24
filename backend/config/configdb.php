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
 * Método encargado de validar la sesión administrativa actual contra la base de datos.
 *
 * @return     bool  Retorna true cuando la sesión sigue activa y false en caso contrario.
 */
function configdb_validar_sesion_administrativa() {
  $dbh  = null;
  $stmt = null;

  try {
    if (session_status() !== PHP_SESSION_ACTIVE) {
      return false;
    }

    if (
      empty($_SESSION['admin_usuario_id'])
      || empty($_SESSION['admin_usuario_sesion_id'])
      || empty($_SESSION['admin_token_sesion'])
    ) {
      return false;
    }

    $dbh = configdb_obtener_conexion();
    $sql = "SELECT
"
         . "  usuario_sesion_id
"
         . "FROM
"
         . "  public.usuarios_sesiones
"
         . "WHERE
"
         . "  usuario_sesion_id = :usuario_sesion_id
"
         . "  AND usuario_id = :usuario_id
"
         . "  AND token = :token
"
         . "  AND estado = B'1'
"
         . "  AND borrado = B'0'
"
         . "  AND fecha_expiracion >= NOW()
"
         . "LIMIT 1;";

    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(':usuario_sesion_id', (int) $_SESSION['admin_usuario_sesion_id'], PDO::PARAM_INT);
    $stmt->bindValue(':usuario_id', (int) $_SESSION['admin_usuario_id'], PDO::PARAM_INT);
    $stmt->bindValue(':token', (string) $_SESSION['admin_token_sesion'], PDO::PARAM_STR);
    $stmt->execute();

    return $stmt->fetch() ? true : false;
  }
  catch (Throwable $throwable) {
    configdb_registrar_log(__FILE__, __FUNCTION__, $throwable->getMessage(), '');

    return false;
  }
  finally {
    if ($stmt) {
      $stmt = null;
    }

    if ($dbh) {
      $dbh = null;
    }
  }
}
/**
 * Método encargado de limpiar la sesión administrativa actual.
 */
function configdb_limpiar_sesion_administrativa() {
  unset(
    $_SESSION['admin_usuario_id'],
    $_SESSION['admin_usuario_login'],
    $_SESSION['admin_usuario_correo'],
    $_SESSION['admin_usuario_nombre_completo'],
    $_SESSION['admin_sw_superusuario'],
    $_SESSION['admin_roles'],
    $_SESSION['admin_token_sesion'],
    $_SESSION['admin_usuario_sesion_id']
  );

  $_SESSION['admin_token'] = bin2hex(random_bytes(32));
}

/**
 * Método encargado de validar la sesión administrativa de tienda contra la base de datos.
 *
 * @return     bool  Retorna true cuando la sesión de tienda sigue activa.
 */
function configdb_validar_sesion_tienda_admin() {
  $dbh  = null;
  $stmt = null;

  try {
    if (session_status() !== PHP_SESSION_ACTIVE) {
      return false;
    }

    if (
      empty($_SESSION['tienda_admin_usuario_id'])
      || empty($_SESSION['tienda_admin_usuario_sesion_id'])
      || empty($_SESSION['tienda_admin_token_sesion'])
    ) {
      return false;
    }

    $dbh = configdb_obtener_conexion();
    $sql = "SELECT
"
         . "  usuario_sesion_tienda_id
"
         . "FROM
"
         . "  public.usuarios_sesiones_tienda
"
         . "WHERE
"
         . "  usuario_sesion_tienda_id = :usuario_sesion_tienda_id
"
         . "  AND usuario_id = :usuario_id
"
         . "  AND token = :token
"
         . "  AND estado = B'1'
"
         . "  AND borrado = B'0'
"
         . "  AND fecha_expiracion >= NOW()
"
         . "LIMIT 1;";

    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(':usuario_sesion_tienda_id', (int) $_SESSION['tienda_admin_usuario_sesion_id'], PDO::PARAM_INT);
    $stmt->bindValue(':usuario_id', (int) $_SESSION['tienda_admin_usuario_id'], PDO::PARAM_INT);
    $stmt->bindValue(':token', (string) $_SESSION['tienda_admin_token_sesion'], PDO::PARAM_STR);
    $stmt->execute();

    return $stmt->fetch() ? true : false;
  }
  catch (Throwable $throwable) {
    configdb_registrar_log(__FILE__, __FUNCTION__, $throwable->getMessage(), '');

    return false;
  }
  finally {
    if ($stmt) {
      $stmt = null;
    }

    if ($dbh) {
      $dbh = null;
    }
  }
}
/**
 * Método encargado de cerrar la sesión de tienda actual en base de datos.
 */
function configdb_cerrar_sesion_tienda_admin() {
  $dbh  = null;
  $stmt = null;

  try {
    if (session_status() !== PHP_SESSION_ACTIVE) {
      return;
    }

    if (
      empty($_SESSION['tienda_admin_usuario_id'])
      || empty($_SESSION['tienda_admin_usuario_sesion_id'])
      || empty($_SESSION['tienda_admin_token_sesion'])
    ) {
      return;
    }

    $dbh = configdb_obtener_conexion();
    $sql = "UPDATE public.usuarios_sesiones_tienda
"
         . "SET
"
         . "  estado = B'0',
"
         . "  fecha_expiracion = NOW(),
"
         . "  usuario_modificacion = :usuario_modificacion,
"
         . "  fecha_modificacion = NOW()
"
         . "WHERE
"
         . "  usuario_sesion_tienda_id = :usuario_sesion_tienda_id
"
         . "  AND usuario_id = :usuario_id
"
         . "  AND token = :token
"
         . "  AND borrado = B'0';";

    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(':usuario_modificacion', (int) $_SESSION['tienda_admin_usuario_id'], PDO::PARAM_INT);
    $stmt->bindValue(':usuario_sesion_tienda_id', (int) $_SESSION['tienda_admin_usuario_sesion_id'], PDO::PARAM_INT);
    $stmt->bindValue(':usuario_id', (int) $_SESSION['tienda_admin_usuario_id'], PDO::PARAM_INT);
    $stmt->bindValue(':token', (string) $_SESSION['tienda_admin_token_sesion'], PDO::PARAM_STR);
    $stmt->execute();
  }
  catch (Throwable $throwable) {
    configdb_registrar_log(__FILE__, __FUNCTION__, $throwable->getMessage(), '');
  }
  finally {
    if ($stmt) {
      $stmt = null;
    }

    if ($dbh) {
      $dbh = null;
    }
  }
}
/**
 * Método encargado de limpiar la sesión administrativa de tienda.
 */
function configdb_limpiar_sesion_tienda_admin() {
  unset(
    $_SESSION['tienda_admin_usuario_id'],
    $_SESSION['tienda_admin_usuario_login'],
    $_SESSION['tienda_admin_usuario_nombre_completo'],
    $_SESSION['tienda_admin_usuario_correo'],
    $_SESSION['tienda_admin_roles'],
    $_SESSION['tienda_admin_token_sesion'],
    $_SESSION['tienda_admin_usuario_sesion_id']
  );

  $_SESSION['tienda_admin_token'] = bin2hex(random_bytes(32));
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
