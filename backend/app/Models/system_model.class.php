<?php
require_once __DIR__ . '/conexion_model.class.php';
class system_model {
  private $con;
  private $dbh;
  private $modulo = __FILE__;
  private $datos  = [];
  private const CONSTANTE = true;
  private $constante;
  public  $usuario_id;
  /**
   * Método constructor
   */
  public function __construct() {
    $this->con        = new conexion_model();
    $this->dbh        = $this->con->pdo();
    $this->usuario_id = 0;
    if (session_status() === PHP_SESSION_ACTIVE && !empty($_SESSION['usuario_id']))
      $this->usuario_id = (int) $_SESSION['usuario_id'];
  }
  /**
   * Método encargado de inicializar la capa base del esquema system
   *
   * @return     array   Arreglo con la información inicial del módulo.
   */
  public function inicializar_modulo() {
    $this->constante = self::CONSTANTE;
    $this->datos     = $this->asignar_datos_iniciales();
    return [
      'estado'    => true,
      'mensaje'   => 'La capa base del esquema system fue inicializada correctamente.',
      'constante' => $this->constante,
      'datos'     => $this->datos
    ];
  }
  /**
   * Método encargado de consultar los temas
   *
   * @return     array   Arreglo con el resultado de la consulta.
   */
  public function listar_temas() {
    return $this->consultar_listado_simple('system.temas');
  }
  /**
   * Método encargado de consultar los tokens de los temas
   *
   * @return     array   Arreglo con el resultado de la consulta.
   */
  public function listar_tema_tokens() {
    return $this->consultar_listado_simple('system.tema_tokens');
  }
  /**
   * Método encargado de consultar los componentes de los temas
   *
   * @return     array   Arreglo con el resultado de la consulta.
   */
  public function listar_tema_componentes() {
    return $this->consultar_listado_simple('system.tema_componentes');
  }
  /**
   * Método encargado de consultar la información de branding
   *
   * @return     array   Arreglo con el resultado de la consulta.
   */
  public function listar_branding() {
    return $this->consultar_listado_simple('system.branding');
  }
  /**
   * Método encargado de consultar los grupos de parámetros
   *
   * @return     array   Arreglo con el resultado de la consulta.
   */
  public function listar_parametro_grupos() {
    return $this->consultar_listado_simple('system.parametro_grupos');
  }
  /**
   * Método encargado de consultar los parámetros
   *
   * @return     array   Arreglo con el resultado de la consulta.
   */
  public function listar_parametros() {
    return $this->consultar_listado_simple('system.parametros');
  }
  /**
   * Método encargado de consultar los valores de los parámetros
   *
   * @return     array   Arreglo con el resultado de la consulta.
   */
  public function listar_parametro_valores() {
    return $this->consultar_listado_simple('system.parametro_valores');
  }
  /**
   * Método encargado de consultar los módulos
   *
   * @return     array   Arreglo con el resultado de la consulta.
   */
  public function listar_modulos() {
    return $this->consultar_listado_simple('system.modulos');
  }
  /**
   * Método encargado de consultar las configuraciones de módulos
   *
   * @return     array   Arreglo con el resultado de la consulta.
   */
  public function listar_modulo_configuraciones() {
    return $this->consultar_listado_simple('system.modulo_configuraciones');
  }
  /**
   * Método encargado de consultar las integraciones
   *
   * @return     array   Arreglo con el resultado de la consulta.
   */
  public function listar_integraciones() {
    return $this->consultar_listado_simple('system.integraciones');
  }
  /**
   * Método encargado de consultar las configuraciones de integraciones
   *
   * @return     array   Arreglo con el resultado de la consulta.
   */
  public function listar_integracion_configuraciones() {
    return $this->consultar_listado_simple('system.integracion_configuraciones');
  }
  /**
   * Método encargado de consultar las plantillas
   *
   * @return     array   Arreglo con el resultado de la consulta.
   */
  public function listar_plantillas() {
    return $this->consultar_listado_simple('system.plantillas');
  }
  /**
   * Método encargado de consultar los menús
   *
   * @return     array   Arreglo con el resultado de la consulta.
   */
  public function listar_menus() {
    return $this->consultar_listado_simple('system.menus');
  }
  /**
   * Método encargado de consultar la bitácora de cambios
   *
   * @return     array   Arreglo con el resultado de la consulta.
   */
  public function listar_bitacora_cambios() {
    return $this->consultar_listado_simple('system.bitacora_cambios');
  }
  /**
   * Método encargado de consultar los logs de aplicación
   *
   * @return     array   Arreglo con el resultado de la consulta.
   */
  public function listar_logs_aplicacion() {
    return $this->consultar_listado_simple('system.logs_aplicacion');
  }
  /**
   * Método encargado de asignar los datos iniciales del módulo
   *
   * @return     array   Arreglo con los datos iniciales.
   */
  private function asignar_datos_iniciales() {
    return [
      'esquema' => 'system',
      'tablas'  => [
        'temas',
        'tema_tokens',
        'tema_componentes',
        'branding',
        'parametro_grupos',
        'parametros',
        'parametro_valores',
        'modulos',
        'modulo_configuraciones',
        'integraciones',
        'integracion_configuraciones',
        'plantillas',
        'menus',
        'bitacora_cambios',
        'logs_aplicacion'
      ]
    ];
  }
  /**
   * Método encargado de consultar listados simples de las tablas del esquema system
   *
   * @param      string  $tabla  Nombre completo de la tabla.
   *
   * @return     array   Arreglo con el resultado de la consulta.
   */
  private function consultar_listado_simple($tabla) {
    $sql = "SELECT *\n"
         . "FROM\n"
         . "  {$tabla}\n"
         . "ORDER BY\n"
         . "  1 ASC;";
    return $this->ejecutar_consulta($sql);
  }
  /**
   * Método encargado de ejecutar la consulta recibida
   *
   * @param      string  $sql         Consulta SQL a ejecutar.
   * @param      array   $parametros  Parámetros para la consulta.
   *
   * @return     array   Arreglo con el resultado de la consulta.
   */
  private function ejecutar_consulta($sql, $parametros = []) {
    $stmt  = null;
    $datos = [];
    if (!$this->dbh instanceof PDO) {
      return [
        'estado'  => false,
        'mensaje' => 'No fue posible establecer conexión con la base de datos.',
        'datos'   => []
      ];
    }
    try {
      $stmt = $this->dbh->prepare($sql);
      $stmt->setFetchMode(PDO::FETCH_ASSOC);
      $this->gestionar_parametros_consulta($stmt, $parametros);
      $stmt->execute();
      while ($registro = $stmt->fetch())
        $datos[] = $registro;
      return [
        'estado'  => true,
        'mensaje' => 'Consulta realizada correctamente.',
        'datos'   => $datos
      ];
    }
    catch (PDOException $e) {
      $this->con->capturar_error_pdo(
        $this->modulo,
        __FUNCTION__,
        $sql,
        $e
      );
      return [
        'estado'  => false,
        'mensaje' => 'No fue posible consultar la información del esquema system.',
        'datos'   => []
      ];
    }
    finally {
      if ($stmt)
        $stmt = null;
    }
  }
  /**
   * Método encargado de asociar los parámetros de la consulta
   *
   * @param      object  $stmt        Sentencia preparada.
   * @param      array   $parametros  Parámetros de la consulta.
   */
  private function gestionar_parametros_consulta(&$stmt, $parametros) {
    foreach ($parametros as $parametro => $valor)
      $stmt->bindValue($parametro, $valor, $this->obtener_tipo_parametro($valor));
  }
  /**
   * Método encargado de obtener el tipo PDO del parámetro recibido
   *
   * @param      mixed  $valor  Valor del parámetro a evaluar.
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
}
?>
