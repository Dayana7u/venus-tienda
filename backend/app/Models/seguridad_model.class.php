<?php
require_once __DIR__ . '/../../config/configdb.php';

class seguridad_model {
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
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }

    $this->configuracion = configdb_obtener_configuracion();
    $this->dbh           = configdb_obtener_conexion();
    $this->usuario_id    = $_SESSION['usuario_id'] ?? 0;
  }
  /**
   * Método encargado de inicializar el módulo seguridad.
   *
   * @return     array  Arreglo con la información inicial del módulo.
   */
  public function seguridad_inicializar() {
    $this->constante = self::CONSTANTE;
    $this->datos     = [
      'tablas' => [
        'usuarios',
        'roles',
        'permisos'
      ],
    ];

    return [
      'estado'    => true,
      'mensaje'   => 'Inicialización correcta.',
      'constante' => $this->constante,
      'datos'     => $this->datos,
    ];
  }
  /**
   * Método encargado de consultar los usuarios.
   *
   * @return     array  Arreglo con el resultado de la consulta.
   */
  public function seguridad_listar_usuarios() {
    return $this->consultar_listado_simple('public.usuarios');
  }
  /**
   * Método encargado de consultar los roles.
   *
   * @return     array  Arreglo con el resultado de la consulta.
   */
  public function seguridad_listar_roles() {
    return $this->consultar_listado_simple('public.roles');
  }
  /**
   * Método encargado de consultar los permisos.
   *
   * @return     array  Arreglo con el resultado de la consulta.
   */
  public function seguridad_listar_permisos() {
    return $this->consultar_listado_simple('public.permisos');
  }
  /**
   * Método encargado de consultar listados simples.
   *
   * @param      string  $tabla  Nombre completo de la tabla.
   *
   * @return     array  Arreglo con el resultado de la consulta.
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
   * Método encargado de ejecutar la consulta recibida.
   *
   * @param      string  $sql         Consulta SQL a ejecutar.
   * @param      array   $parametros  Parámetros para la consulta.
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

      while ($registro = $stmt->fetch()) {
        $datos[] = $registro;
      }

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
        'mensaje' => 'No fue posible consultar la información.',
        'datos'   => [],
      ];
    }
    finally {
      if ($stmt) {
        $stmt = null;
      }
    }
  }
  /**
   * Método encargado de asociar los parámetros de la consulta.
   *
   * @param      PDOStatement  $stmt        Sentencia preparada.
   * @param      array         $parametros  Parámetros de la consulta.
   */
  private function gestionar_parametros_consulta(&$stmt, $parametros) {
    foreach ($parametros as $parametro => $valor) {
      $stmt->bindValue($parametro, $valor, PDO::PARAM_STR);
    }
  }
}
?>
