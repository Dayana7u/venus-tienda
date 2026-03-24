<?php
require_once __DIR__ . '/../Models/tienda_carrito_model.class.php';

$tienda_carrito_model = new tienda_carrito_model();
$accion = $_POST['accion'] ?? '';
$ajax = (
  isset($_POST['ajax'])
  || (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower((string) $_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $slug        = $_POST['slug'] ?? '';
  $cantidad    = (int) ($_POST['cantidad'] ?? 1);
  $redireccion = $_POST['redireccion'] ?? '/carrito/';
  $estado      = false;
  $mensaje     = 'No fue posible procesar la acción del carrito.';

  switch ($accion) {
    case 'agregar':
      $estado = $tienda_carrito_model->agregar_producto_carrito_tienda($slug, $cantidad);
      $mensaje = $estado === true ? 'Item agregado al carrito.' : 'No fue posible agregar el producto.';
      break;

    case 'actualizar':
      $estado = $tienda_carrito_model->actualizar_producto_carrito_tienda($slug, $cantidad);
      $mensaje = $estado === true ? 'Carrito actualizado correctamente.' : 'No fue posible actualizar el producto.';
      break;

    case 'eliminar':
      $estado = $tienda_carrito_model->eliminar_producto_carrito_tienda($slug);
      $mensaje = $estado === true ? 'Producto eliminado del carrito.' : 'No fue posible eliminar el producto.';
      break;

    case 'resumen':
      $estado = true;
      $mensaje = 'Resumen del carrito consultado correctamente.';
      break;
  }

  $carrito = $tienda_carrito_model->consultar_carrito_modulo_tienda()['carrito'] ?? [];

  if ($ajax === true) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
      'estado'  => $estado,
      'mensaje' => $mensaje,
      'carrito' => $carrito,
    ]);
    exit;
  }

  if ($estado === true && $accion !== 'resumen') {
    $_SESSION['tv_mensaje'] = $mensaje;
  }

  header('Location: ' . ($redireccion !== '' ? $redireccion : '/carrito/'));
  exit;
}

$tv_datos = $tienda_carrito_model->consultar_carrito_modulo_tienda();
?>
