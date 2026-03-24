<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

header('Content-Type: application/json; charset=UTF-8');

$accion = $_POST['accion'] ?? '';
$token  = $_POST['token'] ?? '';

if (empty($_SESSION['tienda_publica_token'])) {
  $_SESSION['tienda_publica_token'] = bin2hex(random_bytes(32));
}

if ($token === '' || $_SESSION['tienda_publica_token'] !== $token) {
  echo json_encode([
    'estado'  => false,
    'mensaje' => 'Token inválido.',
    'datos'   => [],
  ]);
  exit;
}

require_once __DIR__ . '/../Models/tienda_publica_model.class.php';

$model = new tienda_publica_model();

switch ($accion) {
  case 'tienda_publica_inicializar':
    echo json_encode($model->tienda_publica_inicializar());
    break;

  case 'tienda_publica_listar_portada':
    echo json_encode($model->tienda_publica_listar_portada());
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
