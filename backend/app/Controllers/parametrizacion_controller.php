<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

header('Content-Type: application/json; charset=UTF-8');

require_once __DIR__ . '/../../config/configdb.php';

if (empty($_SESSION['admin_usuario_id']) || !configdb_validar_sesion_administrativa()) {
  configdb_limpiar_sesion_administrativa();

  echo json_encode([
    'estado'  => false,
    'mensaje' => 'Sesión no válida.',
    'datos'   => [],
  ]);
  exit;
}

$token = $_POST['token'] ?? '';

if (empty($_SESSION['admin_token']) || $_SESSION['admin_token'] !== $token) {
  echo json_encode([
    'estado'  => false,
    'mensaje' => 'Token inválido.',
    'datos'   => [],
  ]);
  exit;
}

require_once __DIR__ . '/../Models/parametrizacion_model.class.php';

$model  = new parametrizacion_model();
$accion = $_POST['accion'] ?? '';

switch ($accion) {
  case 'parametrizacion_inicializar':
    echo json_encode($model->parametrizacion_inicializar());
    break;

  case 'parametrizacion_consultar_registro':
    $seccion     = $_POST['seccion'] ?? '';
    $registro_id = (int) ($_POST['registro_id'] ?? 0);

    echo json_encode($model->parametrizacion_consultar_registro($seccion, $registro_id));
    break;

  case 'parametrizacion_guardar_registro':
    $seccion = $_POST['seccion'] ?? '';
    echo json_encode($model->parametrizacion_guardar_registro($seccion, $_POST));
    break;

  case 'parametrizacion_cambiar_estado_registro':
    $seccion     = $_POST['seccion'] ?? '';
    $registro_id = (int) ($_POST['registro_id'] ?? 0);
    $estado      = $_POST['estado'] ?? '0';

    echo json_encode($model->parametrizacion_cambiar_estado_registro($seccion, $registro_id, $estado));
    break;
  case 'parametrizacion_borrar_registro':
    $seccion     = $_POST['seccion'] ?? '';
    $registro_id = (int) ($_POST['registro_id'] ?? 0);

    echo json_encode($model->parametrizacion_borrar_registro($seccion, $registro_id));
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
