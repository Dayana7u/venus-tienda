<?php
$configdb = require __DIR__ . '/../../config/configdb.php';

class seguridad_model {
  private $conexion;
  private $usuario_id;
  private $modulo;
  private $logs_path;
  private const CONSTANTE = true;
  private $constante = false;

  public function __construct() {
    global $configdb;

    $this->usuario_id = $_SESSION['usuario_id'] ?? 0;
    $this->modulo = __FILE__;
    $this->logs_path = $configdb['logs_path'];

    $dsn = sprintf(
      '%s:host=%s;port=%s;dbname=%s',
      $configdb['driver'],
      $configdb['host'],
      $configdb['port'],
      $configdb['dbname']
    );

    $this->conexion = new PDO(
      $dsn,
      $configdb['user'],
      $configdb['password'],
      $configdb['options']
    );
  }

  public function seguridad_inicializar() {
    $this->constante = self::CONSTANTE;

    return [
      'estado'  => true,
      'mensaje' => 'Inicialización correcta.',
      'datos'   => [
        'constante' => $this->constante,
      ],
    ];
  }

  public function seguridad_listar_usuarios() {
    return $this->ejecutar_consulta_tabla(
      'SELECT * FROM public.usuarios ORDER BY 1 ASC;'
    );
  }

  public function seguridad_listar_roles() {
    return $this->ejecutar_consulta_tabla(
      'SELECT * FROM public.roles ORDER BY 1 ASC;'
    );
  }

  public function seguridad_listar_permisos() {
    return $this->ejecutar_consulta_tabla(
      'SELECT * FROM public.permisos ORDER BY 1 ASC;'
    );
  }

  private function ejecutar_consulta_tabla($sql) {
    $stmt = null;

    try {
      $stmt = $this->conexion->prepare($sql);
      $stmt->execute();

      return [
        'estado'  => true,
        'mensaje' => 'Consulta realizada correctamente.',
        'datos'   => $stmt->fetchAll(),
      ];
    } catch (Throwable $throwable) {
      $this->registrar_log($throwable->getMessage());

      return [
        'estado'  => false,
        'mensaje' => 'No fue posible consultar la información.',
        'datos'   => [],
      ];
    } finally {
      if ($stmt) {
        $stmt = null;
      }
    }
  }

  private function registrar_log($mensaje) {
    $contenido = sprintf(
      "[%s] [%s] [%s] usuario_id=%s %s%s",
      date('Y-m-d H:i:s'),
      'seguridad_model',
      $this->modulo,
      $this->usuario_id,
      $mensaje,
      PHP_EOL
    );

    error_log($contenido, 3, $this->logs_path);
  }
}
