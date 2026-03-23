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

require_once __DIR__ . '/../Models/parametrizacion_model.class.php';

$model  = new parametrizacion_model();
$accion = $_POST['accion'] ?? '';

switch ($accion) {
  case 'parametrizacion_inicializar':
    echo json_encode($model->parametrizacion_inicializar(), JSON_UNESCAPED_UNICODE);
    break;

  case 'parametrizacion_listar_temas':
    echo json_encode($model->parametrizacion_listar_temas(), JSON_UNESCAPED_UNICODE);
    break;

  case 'parametrizacion_listar_branding':
    echo json_encode($model->parametrizacion_listar_branding(), JSON_UNESCAPED_UNICODE);
    break;

  case 'parametrizacion_listar_parametros':
    echo json_encode($model->parametrizacion_listar_parametros(), JSON_UNESCAPED_UNICODE);
    break;

  case 'parametrizacion_listar_modulos':
    echo json_encode($model->parametrizacion_listar_modulos(), JSON_UNESCAPED_UNICODE);
    break;

  case 'parametrizacion_listar_integraciones':
    echo json_encode($model->parametrizacion_listar_integraciones(), JSON_UNESCAPED_UNICODE);
    break;

  case 'parametrizacion_listar_menus':
    echo json_encode($model->parametrizacion_listar_menus(), JSON_UNESCAPED_UNICODE);
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
