<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

header('Content-Type: application/json; charset=UTF-8');

$accion = $_POST['accion'] ?? '';
$token  = $_POST['token'] ?? '';

if (empty($_SESSION['tienda_admin_token'])) {
  $_SESSION['tienda_admin_token'] = bin2hex(random_bytes(32));
}

if ($token === '' || $_SESSION['tienda_admin_token'] !== $token) {
  echo json_encode([
    'estado'  => false,
    'mensaje' => 'Token inválido.',
    'datos'   => [],
  ]);
  exit;
}

require_once __DIR__ . '/../Models/tienda_admin_model.class.php';

$model = new tienda_admin_model();

switch ($accion) {
  case 'tienda_admin_inicializar':
    echo json_encode($model->tienda_admin_inicializar());
    break;

  case 'tienda_admin_listar_dashboard':
    echo json_encode($model->tienda_admin_listar_dashboard());
    break;

  case 'tienda_admin_guardar_categoria':
    echo json_encode($model->tienda_admin_guardar_categoria());
    break;

  case 'tienda_admin_guardar_producto':
    echo json_encode($model->tienda_admin_guardar_producto());
    break;

  case 'tienda_admin_guardar_imagen':
    echo json_encode($model->tienda_admin_guardar_imagen());
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
