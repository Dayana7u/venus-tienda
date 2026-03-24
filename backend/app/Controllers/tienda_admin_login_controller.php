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

require_once __DIR__ . '/../Models/tienda_admin_login_model.class.php';

$model = new tienda_admin_login_model();

switch ($accion) {
  case 'tienda_admin_login_inicializar':
    echo json_encode($model->tienda_admin_login_inicializar());
    break;

  case 'tienda_admin_login_autenticar':
    $login = $_POST['tienda_admin_login_usuario'] ?? '';
    $clave = $_POST['tienda_admin_login_clave'] ?? '';

    echo json_encode($model->tienda_admin_login_autenticar($login, $clave));
    break;

  case 'tienda_admin_login_validar_sesion':
    echo json_encode($model->tienda_admin_login_validar_sesion());
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
