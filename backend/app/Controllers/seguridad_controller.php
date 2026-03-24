<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

header('Content-Type: application/json; charset=UTF-8');

require_once __DIR__ . '/../../config/configdb.php';

$token = $_POST['token'] ?? '';

if (empty($_SESSION['admin_usuario_id']) || !configdb_validar_sesion_administrativa()) {
  configdb_limpiar_sesion_administrativa();

  echo json_encode([
    'estado'  => false,
    'mensaje' => 'Sesión no válida.',
    'datos'   => [],
  ]);
  exit;
}

if (empty($_SESSION['admin_token']) || $_SESSION['admin_token'] !== $token) {
  echo json_encode([
    'estado'  => false,
    'mensaje' => 'Token inválido.',
    'datos'   => [],
  ]);
  exit;
}

require_once __DIR__ . '/../Models/seguridad_model.class.php';

$model  = new seguridad_model();
$accion = $_POST['accion'] ?? '';

switch ($accion) {
  case 'seguridad_inicializar':
    echo json_encode($model->seguridad_inicializar());
    break;

  case 'seguridad_listar_panel':
    echo json_encode($model->seguridad_listar_panel());
    break;

  case 'seguridad_cerrar_sesion':
    $usuario_sesion_id = (int) ($_POST['usuario_sesion_id'] ?? 0);

    echo json_encode($model->seguridad_cerrar_sesion($usuario_sesion_id));
    break;

  case 'seguridad_cerrar_otras_sesiones':
    echo json_encode($model->seguridad_cerrar_otras_sesiones());
    break;

  case 'seguridad_cambiar_clave_usuario':
    $usuario_id          = (int) ($_POST['usuario_id'] ?? 0);
    $clave_nueva         = $_POST['clave_nueva'] ?? '';
    $clave_confirmacion  = $_POST['clave_confirmacion'] ?? '';

    echo json_encode($model->seguridad_cambiar_clave_usuario($usuario_id, $clave_nueva, $clave_confirmacion));
    break;

  default:
    echo json_encode([
      'estado'  => false,
      'mensaje' => 'Acción no válida.',
      'datos'   => [],
    ]);
    break;
}
?>
