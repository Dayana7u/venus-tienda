<?php
require_once __DIR__ . '/../../config/configdb.php';
class parametrizacion_model {
  private $dbh;
  private $configuracion;
  private $modulo = __FILE__;
  private $datos  = [];
  private $constante;
  private const CONSTANTE = true;
  public  $usuario_id;
  /**
   * Método constructor.
   */
  public function __construct() {
    if (session_status() === PHP_SESSION_NONE)
      session_start();

    $this->configuracion = configdb_obtener_configuracion();
    $this->dbh           = configdb_obtener_conexion();
    $this->usuario_id    = $_SESSION['usuario_id'] ?? 0;
  }
  /**
   * Método encargado de inicializar el módulo de parametrización.
   *
   * @return     array  Arreglo con la información inicial del módulo.
   */
  public function parametrizacion_inicializar() {
    $this->constante = self::CONSTANTE;
    $this->datos     = $this->asignar_datos_parametrizacion();

    return [
      'estado'    => true,
      'mensaje'   => 'El módulo de parametrización fue inicializado correctamente.',
      'constante' => $this->constante,
      'datos'     => $this->datos,
    ];
  }
  /**
   * Método encargado de consultar los temas.
   *
   * @return     array  Arreglo con el resultado de la consulta.
   */
  public function parametrizacion_listar_temas() {
    return $this->consultar_listado_simple('system.temas', 'tema_id');
  }
  /**
   * Método encargado de consultar los tokens de temas.
   *
   * @return     array  Arreglo con el resultado de la consulta.
   */
  public function parametrizacion_listar_tema_tokens() {
    return $this->consultar_listado_simple('system.tema_tokens', 'tema_token_id');
  }
  /**
   * Método encargado de consultar los componentes de temas.
   *
   * @return     array  Arreglo con el resultado de la consulta.
   */
  public function parametrizacion_listar_tema_componentes() {
    return $this->consultar_listado_simple('system.tema_componentes', 'tema_componente_id');
  }
  /**
   * Método encargado de consultar el branding.
   *
   * @return     array  Arreglo con el resultado de la consulta.
   */
  public function parametrizacion_listar_branding() {
    return $this->consultar_listado_simple('system.branding', 'branding_id');
  }
  /**
   * Método encargado de consultar los grupos de parámetros.
   *
   * @return     array  Arreglo con el resultado de la consulta.
   */
  public function parametrizacion_listar_parametro_grupos() {
    return $this->consultar_listado_simple('system.parametro_grupos', 'parametro_grupo_id');
  }
  /**
   * Método encargado de consultar los parámetros.
   *
   * @return     array  Arreglo con el resultado de la consulta.
   */
  public function parametrizacion_listar_parametros() {
    return $this->consultar_listado_simple('system.parametros', 'parametro_id');
  }
  /**
   * Método encargado de consultar los valores de parámetros.
   *
   * @return     array  Arreglo con el resultado de la consulta.
   */
  public function parametrizacion_listar_parametro_valores() {
    return $this->consultar_listado_simple('system.parametro_valores', 'parametro_valor_id');
  }
  /**
   * Método encargado de consultar los módulos.
   *
   * @return     array  Arreglo con el resultado de la consulta.
   */
  public function parametrizacion_listar_modulos() {
    return $this->consultar_listado_simple('system.modulos', 'modulo_id');
  }
  /**
   * Método encargado de consultar las configuraciones de módulos.
   *
   * @return     array  Arreglo con el resultado de la consulta.
   */
  public function parametrizacion_listar_modulo_configuraciones() {
    return $this->consultar_listado_simple('system.modulo_configuraciones', 'modulo_configuracion_id');
  }
  /**
   * Método encargado de consultar las integraciones.
   *
   * @return     array  Arreglo con el resultado de la consulta.
   */
  public function parametrizacion_listar_integraciones() {
    return $this->consultar_listado_simple('system.integraciones', 'integracion_id');
  }
  /**
   * Método encargado de consultar las configuraciones de integraciones.
   *
   * @return     array  Arreglo con el resultado de la consulta.
   */
  public function parametrizacion_listar_integracion_configuraciones() {
    return $this->consultar_listado_simple('system.integracion_configuraciones', 'integracion_configuracion_id');
  }
  /**
   * Método encargado de consultar las plantillas.
   *
   * @return     array  Arreglo con el resultado de la consulta.
   */
  public function parametrizacion_listar_plantillas() {
    return $this->consultar_listado_simple('system.plantillas', 'plantilla_id');
  }
  /**
   * Método encargado de consultar los menús.
   *
   * @return     array  Arreglo con el resultado de la consulta.
   */
  public function parametrizacion_listar_menus() {
    return $this->consultar_listado_simple('system.menus', 'menu_id');
  }
  /**
   * Método encargado de asignar la información base del módulo.
   *
   * @return     array  Arreglo con la información inicial.
   */
  private function asignar_datos_parametrizacion() {
    return [
      'temas'                       => $this->parametrizacion_listar_temas()['datos'],
      'tema_tokens'                 => $this->parametrizacion_listar_tema_tokens()['datos'],
      'tema_componentes'            => $this->parametrizacion_listar_tema_componentes()['datos'],
      'branding'                    => $this->parametrizacion_listar_branding()['datos'],
      'parametro_grupos'            => $this->parametrizacion_listar_parametro_grupos()['datos'],
      'parametros'                  => $this->parametrizacion_listar_parametros()['datos'],
      'parametro_valores'           => $this->parametrizacion_listar_parametro_valores()['datos'],
      'modulos'                     => $this->parametrizacion_listar_modulos()['datos'],
      'modulo_configuraciones'      => $this->parametrizacion_listar_modulo_configuraciones()['datos'],
      'integraciones'               => $this->parametrizacion_listar_integraciones()['datos'],
      'integracion_configuraciones' => $this->parametrizacion_listar_integracion_configuraciones()['datos'],
      'plantillas'                  => $this->parametrizacion_listar_plantillas()['datos'],
      'menus'                       => $this->parametrizacion_listar_menus()['datos'],
    ];
  }
  /**
   * Método encargado de consultar listados simples del esquema system.
   *
   * @param      string  $tabla        Nombre completo de la tabla.
   * @param      string  $campo_orden  Campo de orden principal.
   *
   * @return     array  Arreglo con el resultado de la consulta.
   */
  private function consultar_listado_simple($tabla, $campo_orden) {
    $sql = "SELECT
"
         . "  *
"
         . "FROM
"
         . "  {$tabla}
"
         . "WHERE
"
         . "  estado  = B'1'
"
         . "  AND borrado = B'0'
"
         . "ORDER BY
"
         . "  {$campo_orden} ASC;";

    return $this->ejecutar_consulta($sql);
  }
  /**
   * Método encargado de ejecutar la consulta recibida.
   *
   * @param      string  $sql         Consulta SQL a ejecutar.
   * @param      array   $parametros  Parámetros de la consulta.
   *
   * @return     array  Arreglo con el resultado de la consulta.
   */
  private function ejecutar_consulta($sql, $parametros = []) {
    $stmt  = null;
    $datos = [];

    try {
      $stmt = $this->dbh->prepare($sql);
      $this->gestionar_parametros_consulta($stmt, $parametros);
      $stmt->execute();

      while ($registro = $stmt->fetch())
        $datos[] = $registro;

      return [
        'estado'  => true,
        'mensaje' => 'Consulta realizada correctamente.',
        'datos'   => $datos,
      ];
    }
    catch (PDOException $e) {
      configdb_registrar_log(
        $this->modulo,
        __FUNCTION__,
        $e->getMessage(),
        $sql
      );

      return [
        'estado'  => false,
        'mensaje' => 'No fue posible consultar la información de parametrización.',
        'datos'   => [],
      ];
    }
    finally {
      if ($stmt)
        $stmt = null;
    }
  }
  /**
   * Método encargado de asociar los parámetros de la consulta.
   *
   * @param      PDOStatement  $stmt        Sentencia preparada.
   * @param      array         $parametros  Parámetros de la consulta.
   */
  private function gestionar_parametros_consulta(&$stmt, $parametros) {
    foreach ($parametros as $parametro => $valor)
      $stmt->bindValue($parametro, $valor, PDO::PARAM_STR);
  }
}
?>
