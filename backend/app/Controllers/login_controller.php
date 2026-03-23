<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

header('Content-Type: application/json; charset=UTF-8');

$accion = $_POST['accion'] ?? '';
$token  = $_POST['token'] ?? '';

if (empty($_SESSION['admin_token'])) {
  $_SESSION['admin_token'] = bin2hex(random_bytes(32));
}

if ($token === '' || $_SESSION['admin_token'] !== $token) {
  echo json_encode([
    'estado'  => false,
    'mensaje' => 'Token inválido.',
    'datos'   => [],
  ]);
  exit;
}

require_once __DIR__ . '/../Models/login_model.class.php';

$model = new login_model();

switch ($accion) {
  case 'login_inicializar':
    echo json_encode($model->login_inicializar());
    break;

  case 'login_autenticar':
    $login = $_POST['login_usuario'] ?? '';
    $clave = $_POST['login_clave'] ?? '';

    echo json_encode($model->login_autenticar($login, $clave));
    break;

  case 'login_validar_sesion':
    echo json_encode($model->login_validar_sesion());
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
