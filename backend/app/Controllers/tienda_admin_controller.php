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

function tienda_admin_controller_tiene_permiso($codigo) {
  if (($_SESSION['tienda_admin_sw_superusuario'] ?? '0') === '1') {
    return true;
  }

  $permisos = $_SESSION['tienda_admin_permisos'] ?? [];
  return is_array($permisos) && in_array((string) $codigo, $permisos, true);
}

function tienda_admin_controller_responder_sin_permiso() {
  echo json_encode([
    'estado'  => false,
    'mensaje' => 'No tiene permisos para ejecutar esta acción.',
    'datos'   => [],
  ]);
  exit;
}

$model = new tienda_admin_model();

$permiso_accion = '';

switch ($accion) {
  case 'tienda_admin_inicializar':
  case 'tienda_admin_listar_dashboard':
    $permiso_accion = 'TIENDA_LOGIN';
    break;

  case 'tienda_admin_guardar_categoria':
    $permiso_accion = (int) ($_POST['tienda_admin_categoria_id'] ?? 0) > 0 ? 'TIENDA_CATEGORIAS_EDITAR' : 'TIENDA_CATEGORIAS_GUARDAR';
    break;

  case 'tienda_admin_guardar_producto':
    $permiso_accion = (int) ($_POST['tienda_admin_producto_id'] ?? 0) > 0 ? 'TIENDA_PRODUCTOS_EDITAR' : 'TIENDA_PRODUCTOS_GUARDAR';
    break;

  case 'tienda_admin_guardar_imagen':
    $permiso_accion = (int) ($_POST['tienda_admin_imagen_id'] ?? 0) > 0 ? 'TIENDA_IMAGENES_EDITAR' : 'TIENDA_IMAGENES_GUARDAR';
    break;

  case 'tienda_admin_guardar_cliente':
    $permiso_accion = 'TIENDA_CLIENTES_EDITAR';
    break;

  case 'tienda_admin_inactivar_categoria':
    $permiso_accion = 'TIENDA_CATEGORIAS_INACTIVAR';
    break;

  case 'tienda_admin_borrar_categoria':
    $permiso_accion = 'TIENDA_CATEGORIAS_ELIMINAR';
    break;

  case 'tienda_admin_activar_categoria':
    $permiso_accion = 'TIENDA_CATEGORIAS_ACTIVAR';
    break;

  case 'tienda_admin_inactivar_producto':
    $permiso_accion = 'TIENDA_PRODUCTOS_INACTIVAR';
    break;

  case 'tienda_admin_activar_producto':
    $permiso_accion = 'TIENDA_PRODUCTOS_ACTIVAR';
    break;

  case 'tienda_admin_borrar_producto':
    $permiso_accion = 'TIENDA_PRODUCTOS_ELIMINAR';
    break;

  case 'tienda_admin_inactivar_imagen':
    $permiso_accion = 'TIENDA_IMAGENES_INACTIVAR';
    break;

  case 'tienda_admin_activar_imagen':
    $permiso_accion = 'TIENDA_IMAGENES_ACTIVAR';
    break;

  case 'tienda_admin_actualizar_pedido':
    if (trim((string) ($_POST['estado_pago'] ?? '')) === 'pagado') {
      $permiso_accion = 'TIENDA_PEDIDOS_MARCAR_PAGADO';
    }
    else if (trim((string) ($_POST['estado_pedido'] ?? '')) === 'enviado') {
      $permiso_accion = 'TIENDA_PEDIDOS_MARCAR_ENVIADO';
    }
    else {
      $permiso_accion = 'TIENDA_PEDIDOS_VER';
    }
    break;
}

if ($permiso_accion !== '' && !tienda_admin_controller_tiene_permiso($permiso_accion)) {
  tienda_admin_controller_responder_sin_permiso();
}

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

  case 'tienda_admin_guardar_cliente':
    echo json_encode($model->tienda_admin_guardar_cliente());
    break;

  case 'tienda_admin_inactivar_categoria':
    echo json_encode($model->tienda_admin_inactivar_categoria());
    break;

  case 'tienda_admin_activar_categoria':
    echo json_encode($model->tienda_admin_activar_categoria());
    break;

  case 'tienda_admin_borrar_categoria':
    echo json_encode($model->tienda_admin_borrar_categoria());
    break;

  case 'tienda_admin_inactivar_producto':
    echo json_encode($model->tienda_admin_inactivar_producto());
    break;

  case 'tienda_admin_activar_producto':
    echo json_encode($model->tienda_admin_activar_producto());
    break;

  case 'tienda_admin_borrar_producto':
    echo json_encode($model->tienda_admin_borrar_producto());
    break;

  case 'tienda_admin_inactivar_imagen':
    echo json_encode($model->tienda_admin_inactivar_imagen());
    break;

  case 'tienda_admin_activar_imagen':
    echo json_encode($model->tienda_admin_activar_imagen());
    break;

  case 'tienda_admin_actualizar_pedido':
    echo json_encode($model->tienda_admin_actualizar_pedido());
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
