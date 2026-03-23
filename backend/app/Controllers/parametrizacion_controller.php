<?php
if (session_status() === PHP_SESSION_NONE)
  session_start();

header('Content-Type: application/json; charset=utf-8');

$token = $_POST['token'] ?? '';

if (empty($_SESSION['token']) || $_SESSION['token'] !== $token) {
  echo json_encode([
    'estado'  => false,
    'mensaje' => 'Token inválido.',
    'datos'   => []
  ]);
  exit;
}

require_once __DIR__ . '/../Models/parametrizacion_model.class.php';

$parametrizacion = new parametrizacion_model();
$accion          = $_POST['accion'] ?? '';

switch ($accion) {
  case 'parametrizacion_inicializar':
    echo json_encode($parametrizacion->parametrizacion_inicializar());
    break;

  case 'parametrizacion_listar_temas':
    echo json_encode($parametrizacion->parametrizacion_listar_temas());
    break;

  case 'parametrizacion_listar_tema_tokens':
    echo json_encode($parametrizacion->parametrizacion_listar_tema_tokens());
    break;

  case 'parametrizacion_listar_tema_componentes':
    echo json_encode($parametrizacion->parametrizacion_listar_tema_componentes());
    break;

  case 'parametrizacion_listar_branding':
    echo json_encode($parametrizacion->parametrizacion_listar_branding());
    break;

  case 'parametrizacion_listar_parametro_grupos':
    echo json_encode($parametrizacion->parametrizacion_listar_parametro_grupos());
    break;

  case 'parametrizacion_listar_parametros':
    echo json_encode($parametrizacion->parametrizacion_listar_parametros());
    break;

  case 'parametrizacion_listar_parametro_valores':
    echo json_encode($parametrizacion->parametrizacion_listar_parametro_valores());
    break;

  case 'parametrizacion_listar_modulos':
    echo json_encode($parametrizacion->parametrizacion_listar_modulos());
    break;

  case 'parametrizacion_listar_modulo_configuraciones':
    echo json_encode($parametrizacion->parametrizacion_listar_modulo_configuraciones());
    break;

  case 'parametrizacion_listar_integraciones':
    echo json_encode($parametrizacion->parametrizacion_listar_integraciones());
    break;

  case 'parametrizacion_listar_integracion_configuraciones':
    echo json_encode($parametrizacion->parametrizacion_listar_integracion_configuraciones());
    break;

  case 'parametrizacion_listar_plantillas':
    echo json_encode($parametrizacion->parametrizacion_listar_plantillas());
    break;

  case 'parametrizacion_listar_menus':
    echo json_encode($parametrizacion->parametrizacion_listar_menus());
    break;

  default:
    echo json_encode([
      'estado'  => false,
      'mensaje' => 'Acción no válida.',
      'datos'   => []
    ]);
    break;
}
?>
