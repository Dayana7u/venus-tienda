<?php
require_once __DIR__ . '/../Models/tienda_carrito_model.class.php';

$tienda_carrito_model = new tienda_carrito_model();
$es_ajax = (
  ($_POST['ajax'] ?? $_GET['ajax'] ?? '') === '1'
  || strpos((string) ($_SERVER['HTTP_ACCEPT'] ?? ''), 'application/json') !== false
);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $accion = $_POST['accion'] ?? '';
  $slug = $_POST['slug'] ?? '';
  $cantidad = (int) ($_POST['cantidad'] ?? 1);
  $redireccion = $_POST['redireccion'] ?? '/carrito/';
  $mensaje = '';
  $estado = false;

  switch ($accion) {
    case 'agregar':
      $estado = $tienda_carrito_model->agregar_producto_carrito_tienda($slug, $cantidad) === true;
      $mensaje = $estado === true ? 'Producto agregado al carrito.' : 'No fue posible agregar el producto.';
      break;

    case 'actualizar':
      $estado = $tienda_carrito_model->actualizar_producto_carrito_tienda($slug, $cantidad) === true;
      $mensaje = $estado === true ? 'Carrito actualizado correctamente.' : 'No fue posible actualizar el carrito.';
      break;

    case 'eliminar':
      $estado = $tienda_carrito_model->eliminar_producto_carrito_tienda($slug) === true;
      $mensaje = $estado === true ? 'Producto eliminado del carrito.' : 'No fue posible eliminar el producto.';
      break;
  }

  if ($es_ajax === true) {
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode([
      'estado'  => $estado,
      'mensaje' => $mensaje,
      'datos'   => [
        'carrito' => $tienda_carrito_model->consultar_carrito_tienda(),
      ],
    ]);
    exit;
  }

  if ($estado === true) {
    $_SESSION['tv_mensaje'] = $mensaje;
  }

  header('Location: ' . ($redireccion !== '' ? $redireccion : '/carrito/'));
  exit;
}

if ($es_ajax === true) {
  header('Content-Type: application/json; charset=UTF-8');
  echo json_encode([
    'estado'  => true,
    'mensaje' => 'Consulta realizada correctamente.',
    'datos'   => [
      'carrito' => $tienda_carrito_model->consultar_carrito_tienda(),
    ],
  ]);
  exit;
}

$tv_datos = $tienda_carrito_model->consultar_carrito_modulo_tienda();
?>
