<?php
require_once __DIR__ . '/../../config/configdb.php';

class parametrizacion_model {
  private $dbh;
  private $modelo = __FILE__;
  private $usuario_id;
  private $secciones_parametrizacion = [];
  /**
   * Método constructor de la clase.
   */
  public function __construct() {
    $this->dbh        = configdb_obtener_conexion();
    $this->usuario_id = $_SESSION['usuario_id'] ?? 0;
    $this->secciones_parametrizacion = $this->obtener_secciones_parametrizacion();
  }
  /**
   * Método encargado de obtener la información base para inicializar el módulo.
   *
   * @return     array   Datos de inicialización del módulo.
   */
  public function parametrizacion_inicializar() {
    $datos = [
      'secciones_parametrizacion' => $this->secciones_parametrizacion,
      'usuario_id'                => $this->usuario_id
    ];
    return [
      'estado'  => true,
      'mensaje' => 'Inicialización correcta.',
      'datos'   => $datos
    ];
  }
  /**
   * Método encargado de listar los temas.
   *
   * @return     array   Respuesta con el listado de temas.
   */
  public function parametrizacion_listar_temas() {
    // Consulta
      $sql = "SELECT
                sys_tem.*
              FROM
                system.temas AS sys_tem
              ORDER BY
                sys_tem.tema_id; ";
    return $this->consultar_listado_parametrizacion($sql);
  }
  /**
   * Método encargado de listar los tokens de tema.
   *
   * @return     array   Respuesta con el listado de tokens de tema.
   */
  public function parametrizacion_listar_tema_tokens() {
    // Consulta
      $sql = "SELECT
                sys_tem_tok.*
              FROM
                system.tema_tokens AS sys_tem_tok
              ORDER BY
                sys_tem_tok.tema_token_id; ";
    return $this->consultar_listado_parametrizacion($sql);
  }
  /**
   * Método encargado de listar los componentes de tema.
   *
   * @return     array   Respuesta con el listado de componentes de tema.
   */
  public function parametrizacion_listar_tema_componentes() {
    // Consulta
      $sql = "SELECT
                sys_tem_com.*
              FROM
                system.tema_componentes AS sys_tem_com
              ORDER BY
                sys_tem_com.tema_componente_id; ";
    return $this->consultar_listado_parametrizacion($sql);
  }
  /**
   * Método encargado de listar el branding.
   *
   * @return     array   Respuesta con el listado de branding.
   */
  public function parametrizacion_listar_branding() {
    // Consulta
      $sql = "SELECT
                sys_bra.*
              FROM
                system.branding AS sys_bra
              ORDER BY
                sys_bra.branding_id; ";
    return $this->consultar_listado_parametrizacion($sql);
  }
  /**
   * Método encargado de listar los grupos de parámetros.
   *
   * @return     array   Respuesta con el listado de grupos de parámetros.
   */
  public function parametrizacion_listar_parametro_grupos() {
    // Consulta
      $sql = "SELECT
                sys_par_gru.*
              FROM
                system.parametro_grupos AS sys_par_gru
              ORDER BY
                sys_par_gru.parametro_grupo_id; ";
    return $this->consultar_listado_parametrizacion($sql);
  }
  /**
   * Método encargado de listar los parámetros.
   *
   * @return     array   Respuesta con el listado de parámetros.
   */
  public function parametrizacion_listar_parametros() {
    // Consulta
      $sql = "SELECT
                sys_par.*
              FROM
                system.parametros AS sys_par
              ORDER BY
                sys_par.parametro_id; ";
    return $this->consultar_listado_parametrizacion($sql);
  }
  /**
   * Método encargado de listar los valores de parámetros.
   *
   * @return     array   Respuesta con el listado de valores de parámetros.
   */
  public function parametrizacion_listar_parametro_valores() {
    // Consulta
      $sql = "SELECT
                sys_par_val.*
              FROM
                system.parametro_valores AS sys_par_val
              ORDER BY
                sys_par_val.parametro_valor_id; ";
    return $this->consultar_listado_parametrizacion($sql);
  }
  /**
   * Método encargado de listar los módulos.
   *
   * @return     array   Respuesta con el listado de módulos.
   */
  public function parametrizacion_listar_modulos() {
    // Consulta
      $sql = "SELECT
                sys_mod.*
              FROM
                system.modulos AS sys_mod
              ORDER BY
                sys_mod.modulo_id; ";
    return $this->consultar_listado_parametrizacion($sql);
  }
  /**
   * Método encargado de listar las configuraciones de módulos.
   *
   * @return     array   Respuesta con el listado de configuraciones de módulos.
   */
  public function parametrizacion_listar_modulo_configuraciones() {
    // Consulta
      $sql = "SELECT
                sys_mod_con.*
              FROM
                system.modulo_configuraciones AS sys_mod_con
              ORDER BY
                sys_mod_con.modulo_configuracion_id; ";
    return $this->consultar_listado_parametrizacion($sql);
  }
  /**
   * Método encargado de listar las integraciones.
   *
   * @return     array   Respuesta con el listado de integraciones.
   */
  public function parametrizacion_listar_integraciones() {
    // Consulta
      $sql = "SELECT
                sys_int.*
              FROM
                system.integraciones AS sys_int
              ORDER BY
                sys_int.integracion_id; ";
    return $this->consultar_listado_parametrizacion($sql);
  }
  /**
   * Método encargado de listar las configuraciones de integraciones.
   *
   * @return     array   Respuesta con el listado de configuraciones de integraciones.
   */
  public function parametrizacion_listar_integracion_configuraciones() {
    // Consulta
      $sql = "SELECT
                sys_int_con.*
              FROM
                system.integracion_configuraciones AS sys_int_con
              ORDER BY
                sys_int_con.integracion_configuracion_id; ";
    return $this->consultar_listado_parametrizacion($sql);
  }
  /**
   * Método encargado de listar las plantillas.
   *
   * @return     array   Respuesta con el listado de plantillas.
   */
  public function parametrizacion_listar_plantillas() {
    // Consulta
      $sql = "SELECT
                sys_pla.*
              FROM
                system.plantillas AS sys_pla
              ORDER BY
                sys_pla.plantilla_id; ";
    return $this->consultar_listado_parametrizacion($sql);
  }
  /**
   * Método encargado de listar los menús.
   *
   * @return     array   Respuesta con el listado de menús.
   */
  public function parametrizacion_listar_menus() {
    // Consulta
      $sql = "SELECT
                sys_men.*
              FROM
                system.menus AS sys_men
              ORDER BY
                sys_men.menu_id; ";
    return $this->consultar_listado_parametrizacion($sql);
  }
  /**
   * Método encargado de ejecutar una consulta de listado para parametrización.
   *
   * @param      string  $sql  Consulta SQL a ejecutar.
   *
   * @return     array   Respuesta del listado ejecutado.
   */
  private function consultar_listado_parametrizacion($sql) {
    $stmt  = null;
    $datos = [];
    try {
      $stmt = $this->dbh->prepare($sql);
      $stmt->setFetchMode(PDO::FETCH_ASSOC);
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
      $this->registrar_log(__FUNCTION__, 'No fue posible consultar la información.', $e->getMessage());
      return [
        'estado'  => false,
        'mensaje' => 'No fue posible consultar la información.',
        'datos'   => []
      ];
    }
    finally {
      if ($stmt)
        $stmt = null;
    }
  }
  /**
   * Método encargado de construir la configuración base de secciones del módulo.
   *
   * @return     array   Secciones disponibles para parametrización.
   */
  private function obtener_secciones_parametrizacion() {
    $datos = [
      'temas'                       => 'Temas',
      'tema_tokens'                 => 'Tokens de tema',
      'tema_componentes'            => 'Componentes de tema',
      'branding'                    => 'Branding',
      'parametro_grupos'            => 'Grupos de parámetros',
      'parametros'                  => 'Parámetros',
      'parametro_valores'           => 'Valores de parámetros',
      'modulos'                     => 'Módulos',
      'modulo_configuraciones'      => 'Configuraciones de módulos',
      'integraciones'               => 'Integraciones',
      'integracion_configuraciones' => 'Configuraciones de integraciones',
      'plantillas'                  => 'Plantillas',
      'menus'                       => 'Menús'
    ];
    return $datos;
  }
  /**
   * Método encargado de registrar novedades técnicas en el archivo log.
   *
   * @param      string  $funcion  Función que genera la novedad.
   * @param      string  $mensaje  Mensaje principal del log.
   * @param      string  $detalle  Detalle adicional del log.
   */
  private function registrar_log($funcion, $mensaje, $detalle = '') {
    configdb_registrar_log(
      $this->modelo,
      $funcion,
      $mensaje,
      $detalle
    );
  }
}
?>
