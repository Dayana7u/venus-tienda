<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

require_once __DIR__ . '/../Models/tienda_admin_model.class.php';

$tienda_admin_model = new tienda_admin_model();
$editar_producto = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $accion = $_POST['accion'] ?? '';
  $usuario_id = (int) ($_SESSION['admin_usuario_id'] ?? 1);
  $resultado = false;
  $mensaje = 'No fue posible procesar la acción en el panel tienda.';

  switch ($accion) {
    case 'guardar_categoria':
      $resultado = $tienda_admin_model->guardar_categoria_tienda_admin($_POST, $usuario_id);
      $mensaje = $resultado === true ? 'Categoría registrada correctamente.' : 'No fue posible registrar la categoría.';
      break;

    case 'guardar_producto':
      $resultado = $tienda_admin_model->guardar_producto_tienda_admin($_POST, $usuario_id);
      $mensaje = $resultado === true ? 'Producto guardado correctamente.' : 'No fue posible guardar el producto.';
      break;

    case 'cambiar_estado_producto':
      $resultado = $tienda_admin_model->cambiar_estado_producto_tienda_admin((int) ($_POST['producto_id'] ?? 0), $usuario_id);
      $mensaje = $resultado === true ? 'Estado del producto actualizado correctamente.' : 'No fue posible actualizar el estado.';
      break;
  }

  $_SESSION['tienda_admin_mensaje'] = $mensaje;
  header('Location: /admin/tienda/');
  exit;
}

if (!empty($_GET['editar_producto_id'])) {
  $editar_producto = $tienda_admin_model->consultar_producto_tienda_admin((int) $_GET['editar_producto_id']);
}

$tv_admin_datos = [
  'resumen'          => $tienda_admin_model->consultar_resumen_tienda_admin(),
  'categorias'       => $tienda_admin_model->consultar_categorias_tienda_admin(),
  'productos'        => $tienda_admin_model->consultar_productos_tienda_admin(),
  'editar_producto'  => $editar_producto,
];
?>
