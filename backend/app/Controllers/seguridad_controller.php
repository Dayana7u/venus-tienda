<?php
if (session_status() === PHP_SESSION_NONE)
  session_start();

header('Content-Type: application/json; charset=UTF-8');

$token = $_POST['token'] ?? '';

if (empty($_SESSION['token']) || $_SESSION['token'] !== $token) {
  echo json_encode([
    'estado'  => false,
    'mensaje' => 'Token inválido.',
    'datos'   => []
  ], JSON_UNESCAPED_UNICODE);
  exit;
}

require_once __DIR__ . '/../Models/seguridad_model.class.php';

$model  = new seguridad_model();
$accion = $_POST['accion'] ?? '';

switch ($accion) {
  case 'seguridad_inicializar':
    echo json_encode($model->seguridad_inicializar(), JSON_UNESCAPED_UNICODE);
    break;

  case 'seguridad_listar_usuarios':
    echo json_encode($model->seguridad_listar_usuarios(), JSON_UNESCAPED_UNICODE);
    break;

  case 'seguridad_listar_roles':
    echo json_encode($model->seguridad_listar_roles(), JSON_UNESCAPED_UNICODE);
    break;

  case 'seguridad_listar_permisos':
    echo json_encode($model->seguridad_listar_permisos(), JSON_UNESCAPED_UNICODE);
    break;

  default:
    echo json_encode([
      'estado'  => false,
      'mensaje' => 'Acción no válida.',
      'datos'   => []
    ], JSON_UNESCAPED_UNICODE);
    break;
}
?>
