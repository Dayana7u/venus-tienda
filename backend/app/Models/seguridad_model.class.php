<?php
require_once __DIR__ . '/conexion_model.class.php';
class seguridad_model {
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
   * Método encargado de inicializar el módulo de seguridad
   *
   * @return     array   Arreglo con la información inicial del módulo.
   */
  public function seguridad_inicializar() {
    $this->constante = self::CONSTANTE;
    $this->datos     = [
      'modulo'    => 'seguridad',
      'esquema'   => 'public',
      'constante' => $this->constante
    ];
    return [
      'estado'  => true,
      'mensaje' => 'Inicialización correcta.',
      'datos'   => $this->datos
    ];
  }
  /**
   * Método encargado de consultar los usuarios
   *
   * @return     array   Arreglo con el resultado de la consulta.
   */
  public function seguridad_listar_usuarios() {
    return $this->ejecutar_consulta_tabla('public.usuarios');
  }
  /**
   * Método encargado de consultar los roles
   *
   * @return     array   Arreglo con el resultado de la consulta.
   */
  public function seguridad_listar_roles() {
    return $this->ejecutar_consulta_tabla('public.roles');
  }
  /**
   * Método encargado de consultar los permisos
   *
   * @return     array   Arreglo con el resultado de la consulta.
   */
  public function seguridad_listar_permisos() {
    return $this->ejecutar_consulta_tabla('public.permisos');
  }
  /**
   * Método encargado de ejecutar el listado de una tabla del esquema public
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
        'mensaje' => 'No fue posible consultar la información de seguridad.',
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
