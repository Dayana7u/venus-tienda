<?php
require_once dirname(__DIR__, 2).'/config/database.php';
class conexion_model {
  private $con;
  private $dbh;
  private $modulo = __FILE__;
  private $host;
  private $puerto;
  private $base_datos;
  private $usuario;
  private $clave;
  private $charset;
  private $ruta_log;
  private $contexto = [];
  private const CONSTANTE = 'constante';
  private $constante;
  public $usuario_id;
  /**
   * Método constructor
   */
  public function __construct() {
    $this->inicializar_variables();
    $this->conectar();
  }
  /**
   * Método encargado de inicializar las variables del módulo
   */
  private function inicializar_variables() {
    $constante        = self::CONSTANTE;
    $this->$constante = false;
    $this->host       = APP_DB_HOST;
    $this->puerto     = APP_DB_PORT;
    $this->base_datos = APP_DB_NAME;
    $this->usuario    = APP_DB_USER;
    $this->clave      = APP_DB_PASSWORD;
    $this->charset    = APP_DB_CHARSET;
    $this->ruta_log   = APP_LOG_RUTA;
    $this->usuario_id = 0;
    if (session_status() === PHP_SESSION_ACTIVE && !empty($_SESSION['usuario_id']))
      $this->usuario_id = (int) $_SESSION['usuario_id'];
  }
  /**
   * Método encargado de establecer la conexión a la base de datos
   */
  private function conectar() {
    $this->con = "pgsql:host={$this->host};port={$this->puerto};dbname={$this->base_datos};options='--client_encoding={$this->charset}'";
    try {
      $this->dbh = new PDO($this->con, $this->usuario, $this->clave);
      $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $this->dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
      $this->dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }
    catch (PDOException $e) {
      $this->capturar_error_pdo($this->modulo, __FUNCTION__, '', $e);
    }
  }
  /**
   * Método encargado de retornar la instancia PDO activa
   *
   * @return     PDO|null   Instancia activa de PDO. Retorna null cuando no hay conexión.
   */
  public function pdo() {
    return $this->dbh;
  }
  /**
   * Método encargado de obtener múltiples registros de la base de datos
   *
   * @param      string  $sql         Consulta SQL a ejecutar
   * @param      array   $parametros  Parámetros a asociar en la consulta
   *
   * @return     array   Arreglo con los registros encontrados.
   */
  public function obtener_registros($sql, $parametros = []) {
    $stmt = null;
    $datos = [];
    try {
      $stmt = $this->dbh->prepare($sql);
      $stmt->setFetchMode(PDO::FETCH_ASSOC);
      $this->asignar_parametros($stmt, $parametros);
      $stmt->execute();
      while ($registro = $stmt->fetch())
        $datos[] = $registro;
    }
    catch (PDOException $e) {
      $this->capturar_error_pdo($this->modulo, __FUNCTION__, $sql, $e);
    }
    finally {
      if ($stmt)
        $stmt = null;
    }
    return $datos;
  }
  /**
   * Método encargado de obtener un único registro de la base de datos
   *
   * @param      string  $sql         Consulta SQL a ejecutar
   * @param      array   $parametros  Parámetros a asociar en la consulta
   *
   * @return     array|null   Registro encontrado. Retorna null cuando no hay información.
   */
  public function obtener_registro($sql, $parametros = []) {
    $stmt = null;
    $dato = null;
    try {
      $stmt = $this->dbh->prepare($sql);
      $stmt->setFetchMode(PDO::FETCH_ASSOC);
      $this->asignar_parametros($stmt, $parametros);
      $stmt->execute();
      $dato = $stmt->fetch();
    }
    catch (PDOException $e) {
      $this->capturar_error_pdo($this->modulo, __FUNCTION__, $sql, $e);
    }
    finally {
      if ($stmt)
        $stmt = null;
    }
    return $dato;
  }
  /**
   * Método encargado de ejecutar sentencias de inserción, actualización o borrado
   *
   * @param      string  $sql         Consulta SQL a ejecutar
   * @param      array   $parametros  Parámetros a asociar en la consulta
   *
   * @return     bool   Retorna true cuando la sentencia se ejecuta correctamente y false en caso contrario.
   */
  public function ejecutar_sentencia($sql, $parametros = []) {
    $stmt = null;
    $resultado = false;
    try {
      $stmt = $this->dbh->prepare($sql);
      $this->asignar_parametros($stmt, $parametros);
      $resultado = $stmt->execute() ? true : false;
    }
    catch (PDOException $e) {
      $this->capturar_error_pdo($this->modulo, __FUNCTION__, $sql, $e);
    }
    finally {
      if ($stmt)
        $stmt = null;
    }
    return $resultado;
  }
  /**
   * Método encargado de asociar los parámetros al statement de PDO
   *
   * @param      object  $stmt        Instancia del statement preparado
   * @param      array   $parametros  Parámetros a asociar en la consulta
   */
  private function asignar_parametros(&$stmt, $parametros) {
    foreach ($parametros as $parametro => $valor)
      $stmt->bindValue($parametro, $valor, $this->obtener_tipo_parametro($valor));
  }
  /**
   * Método encargado de obtener el tipo PDO del parámetro recibido
   *
   * @param      mixed  $valor  Valor del parámetro a evaluar
   *
   * @return     integer   Tipo PDO correspondiente al valor recibido.
   */
  private function obtener_tipo_parametro($valor) {
    if (is_int($valor))
      return PDO::PARAM_INT;
    if (is_bool($valor))
      return PDO::PARAM_BOOL;
    if (is_null($valor))
      return PDO::PARAM_NULL;
    return PDO::PARAM_STR;
  }
  /**
   * Método encargado de capturar el error de PDO y registrar la trazabilidad técnica
   *
   * @param      string  $modulo   Ruta del fichero que originó el error
   * @param      string  $funcion  Nombre de la función que originó el error
   * @param      string  $sql      Consulta SQL asociada al error
   * @param      object  $error    Objeto de excepción capturado
   */
  public function capturar_error_pdo($modulo, $funcion, $sql, $error) {
    $this->contexto = [
      'sql'  => $sql,
      'tipo' => get_class($error)
    ];
    $registro = [
      'nivel'    => 'ERROR',
      'modulo'   => 'BASE_DATOS',
      'archivo'  => $modulo,
      'funcion'  => $funcion,
      'linea'    => $error->getLine(),
      'mensaje'  => $error->getMessage(),
      'detalle'  => $sql,
      'contexto' => json_encode($this->contexto, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
    ];
    $this->escribir_log_archivo($registro);
    $this->guardar_log_aplicacion($registro);
  }
  /**
   * Método encargado de registrar el log en archivo local
   *
   * @param      array  $registro  Información del error a registrar
   */
  private function escribir_log_archivo($registro) {
    $directorio = dirname($this->ruta_log);
    if (!is_dir($directorio))
      mkdir($directorio, 0777, true);
    $linea = sprintf(
      "[%s] [%s] [%s::%s] %s | linea: %s | detalle: %s | contexto: %s%s",
      date('Y-m-d H:i:s'),
      $registro['nivel'],
      $registro['archivo'],
      $registro['funcion'],
      $registro['mensaje'],
      $registro['linea'],
      $registro['detalle'],
      $registro['contexto'],
      PHP_EOL
    );
    file_put_contents($this->ruta_log, $linea, FILE_APPEND);
  }
  /**
   * Método encargado de registrar el log en la tabla system.logs_aplicacion
   *
   * @param      array  $registro  Información del error a registrar
   */
  private function guardar_log_aplicacion($registro) {
    $stmt = null;
    if (!$this->dbh instanceof PDO)
      return;
    $sql = "INSERT INTO system.logs_aplicacion
              (
                nivel,
                modulo,
                archivo,
                funcion,
                linea,
                mensaje,
                detalle,
                contexto,
                estado,
                borrado,
                usuario_creacion,
                fecha_creacion,
                usuario_modificacion,
                fecha_modificacion,
                usuario_borrado,
                fecha_borrado
              )
            VALUES
              (
                :nivel,
                :modulo,
                :archivo,
                :funcion,
                :linea,
                :mensaje,
                :detalle,
                :contexto,
                B'1',
                B'0',
                :usuario_creacion,
                NOW(),
                NULL,
                NULL,
                NULL,
                NULL
              );";
    try {
      $stmt = $this->dbh->prepare($sql);
      $stmt->bindValue(':nivel', $registro['nivel'], PDO::PARAM_STR);
      $stmt->bindValue(':modulo', $registro['modulo'], PDO::PARAM_STR);
      $stmt->bindValue(':archivo', $registro['archivo'], PDO::PARAM_STR);
      $stmt->bindValue(':funcion', $registro['funcion'], PDO::PARAM_STR);
      $stmt->bindValue(':linea', $registro['linea'], PDO::PARAM_INT);
      $stmt->bindValue(':mensaje', $registro['mensaje'], PDO::PARAM_STR);
      $stmt->bindValue(':detalle', $registro['detalle'], PDO::PARAM_STR);
      $stmt->bindValue(':contexto', $registro['contexto'], PDO::PARAM_STR);
      $stmt->bindValue(':usuario_creacion', $this->usuario_id, PDO::PARAM_INT);
      $stmt->execute();
    }
    catch (PDOException $e) {
      $linea = sprintf(
        "[%s] [ERROR] [%s::%s] %s%s",
        date('Y-m-d H:i:s'),
        $this->modulo,
        __FUNCTION__,
        $e->getMessage(),
        PHP_EOL
      );
      file_put_contents($this->ruta_log, $linea, FILE_APPEND);
    }
    finally {
      if ($stmt)
        $stmt = null;
    }
  }
}
?>
