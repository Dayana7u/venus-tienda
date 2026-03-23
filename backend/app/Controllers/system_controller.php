<?php
if (session_status() === PHP_SESSION_NONE)
  session_start();

header('Content-Type: application/json; charset=UTF-8');

$token = $_POST['token'] ?? '';

if (empty($_SESSION['token']) || $_SESSION['token'] !== $token) {
  echo json_encode([
    'estado'  => false,
    'mensaje' => 'Token inválido.',
    'datos'   => [],
  ]);
  exit;
}

require_once __DIR__ . '/../Models/system_model.class.php';

$model  = new system_model();
$accion = $_POST['accion'] ?? '';

switch ($accion) {
  case 'inicializar_modulo':
    echo json_encode($model->inicializar_modulo());
    break;

  case 'listar_temas':
    echo json_encode($model->listar_temas());
    break;

  case 'listar_tema_tokens':
    echo json_encode($model->listar_tema_tokens());
    break;

  case 'listar_tema_componentes':
    echo json_encode($model->listar_tema_componentes());
    break;

  case 'listar_branding':
    echo json_encode($model->listar_branding());
    break;

  case 'listar_parametro_grupos':
    echo json_encode($model->listar_parametro_grupos());
    break;

  case 'listar_parametros':
    echo json_encode($model->listar_parametros());
    break;

  case 'listar_parametro_valores':
    echo json_encode($model->listar_parametro_valores());
    break;

  case 'listar_modulos':
    echo json_encode($model->listar_modulos());
    break;

  case 'listar_modulo_configuraciones':
    echo json_encode($model->listar_modulo_configuraciones());
    break;

  case 'listar_integraciones':
    echo json_encode($model->listar_integraciones());
    break;

  case 'listar_integracion_configuraciones':
    echo json_encode($model->listar_integracion_configuraciones());
    break;

  case 'listar_plantillas':
    echo json_encode($model->listar_plantillas());
    break;

  case 'listar_menus':
    echo json_encode($model->listar_menus());
    break;

  case 'listar_bitacora_cambios':
    echo json_encode($model->listar_bitacora_cambios());
    break;

  case 'listar_logs_aplicacion':
    echo json_encode($model->listar_logs_aplicacion());
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
