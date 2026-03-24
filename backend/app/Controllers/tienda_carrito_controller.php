<?php
require_once __DIR__ . '/../Models/tienda_carrito_model.class.php';

$tienda_carrito_model = new tienda_carrito_model();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $accion      = $_POST['accion'] ?? '';
  $slug        = $_POST['slug'] ?? '';
  $cantidad    = (int) ($_POST['cantidad'] ?? 1);
  $redireccion = $_POST['redireccion'] ?? '/carrito/';

  switch ($accion) {
    case 'agregar':
      if ($tienda_carrito_model->agregar_producto_carrito_tienda($slug, $cantidad) === true) {
        $_SESSION['tv_mensaje'] = 'Producto agregado al carrito.';
      }
      break;

    case 'actualizar':
      if ($tienda_carrito_model->actualizar_producto_carrito_tienda($slug, $cantidad) === true) {
        $_SESSION['tv_mensaje'] = 'Carrito actualizado correctamente.';
      }
      break;

    case 'eliminar':
      if ($tienda_carrito_model->eliminar_producto_carrito_tienda($slug) === true) {
        $_SESSION['tv_mensaje'] = 'Producto eliminado del carrito.';
      }
      break;
  }

  header('Location: ' . ($redireccion !== '' ? $redireccion : '/carrito/'));
  exit;
}

$tv_datos = $tienda_carrito_model->consultar_carrito_modulo_tienda();
?>
