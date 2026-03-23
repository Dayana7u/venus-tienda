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
    if (session_status() === PHP_SESSION_NONE)
      session_start();

    $this->configuracion = configdb_obtener_configuracion();
    $this->dbh           = configdb_obtener_conexion();
    $this->usuario_id    = $_SESSION['usuario_id'] ?? 0;
  }
  /**
   * Método encargado de inicializar el módulo de seguridad.
   *
   * @return     array  Arreglo con la información inicial.
   */
  public function seguridad_inicializar() {
    $this->constante = self::CONSTANTE;
    $this->datos     = [
      'usuarios' => $this->seguridad_listar_usuarios()['datos'],
      'roles'    => $this->seguridad_listar_roles()['datos'],
      'permisos' => $this->seguridad_listar_permisos()['datos'],
    ];

    return [
      'estado'    => true,
      'mensaje'   => 'El módulo de seguridad fue inicializado correctamente.',
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
    return $this->consultar_listado_simple('public.usuarios', 'usuario_id');
  }
  /**
   * Método encargado de consultar los roles.
   *
   * @return     array  Arreglo con el resultado de la consulta.
   */
  public function seguridad_listar_roles() {
    return $this->consultar_listado_simple('public.roles', 'rol_id');
  }
  /**
   * Método encargado de consultar los permisos.
   *
   * @return     array  Arreglo con el resultado de la consulta.
   */
  public function seguridad_listar_permisos() {
    return $this->consultar_listado_simple('public.permisos', 'permiso_id');
  }
  /**
   * Método encargado de consultar listados simples del esquema public.
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
      foreach ($parametros as $parametro => $valor)
        $stmt->bindValue($parametro, $valor, PDO::PARAM_STR);
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
        'mensaje' => 'No fue posible consultar la información de seguridad.',
        'datos'   => [],
      ];
    }
    finally {
      if ($stmt)
        $stmt = null;
    }
  }
}
?>
