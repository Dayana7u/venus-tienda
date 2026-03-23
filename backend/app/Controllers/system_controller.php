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

require_once __DIR__ . '/../Models/system_model.class.php';

$model  = new system_model();
$accion = $_POST['accion'] ?? '';

switch ($accion) {
  case 'inicializar_modulo':
    echo json_encode($model->inicializar_modulo(), JSON_UNESCAPED_UNICODE);
    break;

  case 'listar_temas':
    echo json_encode($model->listar_temas(), JSON_UNESCAPED_UNICODE);
    break;

  case 'listar_tema_tokens':
    echo json_encode($model->listar_tema_tokens(), JSON_UNESCAPED_UNICODE);
    break;

  case 'listar_tema_componentes':
    echo json_encode($model->listar_tema_componentes(), JSON_UNESCAPED_UNICODE);
    break;

  case 'listar_branding':
    echo json_encode($model->listar_branding(), JSON_UNESCAPED_UNICODE);
    break;

  case 'listar_parametro_grupos':
    echo json_encode($model->listar_parametro_grupos(), JSON_UNESCAPED_UNICODE);
    break;

  case 'listar_parametros':
    echo json_encode($model->listar_parametros(), JSON_UNESCAPED_UNICODE);
    break;

  case 'listar_parametro_valores':
    echo json_encode($model->listar_parametro_valores(), JSON_UNESCAPED_UNICODE);
    break;

  case 'listar_modulos':
    echo json_encode($model->listar_modulos(), JSON_UNESCAPED_UNICODE);
    break;

  case 'listar_modulo_configuraciones':
    echo json_encode($model->listar_modulo_configuraciones(), JSON_UNESCAPED_UNICODE);
    break;

  case 'listar_integraciones':
    echo json_encode($model->listar_integraciones(), JSON_UNESCAPED_UNICODE);
    break;

  case 'listar_integracion_configuraciones':
    echo json_encode($model->listar_integracion_configuraciones(), JSON_UNESCAPED_UNICODE);
    break;

  case 'listar_plantillas':
    echo json_encode($model->listar_plantillas(), JSON_UNESCAPED_UNICODE);
    break;

  case 'listar_menus':
    echo json_encode($model->listar_menus(), JSON_UNESCAPED_UNICODE);
    break;

  case 'listar_bitacora_cambios':
    echo json_encode($model->listar_bitacora_cambios(), JSON_UNESCAPED_UNICODE);
    break;

  case 'listar_logs_aplicacion':
    echo json_encode($model->listar_logs_aplicacion(), JSON_UNESCAPED_UNICODE);
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
