<?php
require_once __DIR__ . '/conexion_model.class.php';
class parametrizacion_model {
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
   * Método encargado de inicializar el módulo de parametrización
   *
   * @return     array   Arreglo con la información inicial del módulo.
   */
  public function parametrizacion_inicializar() {
    $this->constante = self::CONSTANTE;
    $this->datos     = [
      'modulo'    => 'parametrizacion',
      'esquema'   => 'system',
      'constante' => $this->constante
    ];
    return [
      'estado'  => true,
      'mensaje' => 'Inicialización correcta.',
      'datos'   => $this->datos
    ];
  }
  /**
   * Método encargado de consultar los temas
   *
   * @return     array   Arreglo con el resultado de la consulta.
   */
  public function parametrizacion_listar_temas() {
    return $this->ejecutar_consulta_tabla('system.temas');
  }
  /**
   * Método encargado de consultar la información de branding
   *
   * @return     array   Arreglo con el resultado de la consulta.
   */
  public function parametrizacion_listar_branding() {
    return $this->ejecutar_consulta_tabla('system.branding');
  }
  /**
   * Método encargado de consultar los parámetros
   *
   * @return     array   Arreglo con el resultado de la consulta.
   */
  public function parametrizacion_listar_parametros() {
    return $this->ejecutar_consulta_tabla('system.parametros');
  }
  /**
   * Método encargado de consultar los módulos
   *
   * @return     array   Arreglo con el resultado de la consulta.
   */
  public function parametrizacion_listar_modulos() {
    return $this->ejecutar_consulta_tabla('system.modulos');
  }
  /**
   * Método encargado de consultar las integraciones
   *
   * @return     array   Arreglo con el resultado de la consulta.
   */
  public function parametrizacion_listar_integraciones() {
    return $this->ejecutar_consulta_tabla('system.integraciones');
  }
  /**
   * Método encargado de consultar los menús
   *
   * @return     array   Arreglo con el resultado de la consulta.
   */
  public function parametrizacion_listar_menus() {
    return $this->ejecutar_consulta_tabla('system.menus');
  }
  /**
   * Método encargado de ejecutar el listado de una tabla del esquema system
   *
   * @param      string  $tabla  Nombre completo de la tabla.
   *
   * @return     array   Arreglo con el resultado de la consulta.
   */
  private function ejecutar_consulta_tabla($tabla) {
    $stmt = null;
    $sql  = "SELECT *\n"
          . "FROM\n"
          . "  {$tabla}\n"
          . "ORDER BY\n"
          . "  1 ASC;";
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
      $stmt->execute();
      return [
        'estado'  => true,
        'mensaje' => 'Consulta realizada correctamente.',
        'datos'   => $stmt->fetchAll()
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
        'mensaje' => 'No fue posible consultar la información de parametrización.',
        'datos'   => []
      ];
    }
    finally {
      if ($stmt)
        $stmt = null;
    }
  }
}
?>
