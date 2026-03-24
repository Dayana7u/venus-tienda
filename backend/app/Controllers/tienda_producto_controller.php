<?php
require_once __DIR__ . '/../Models/tienda_producto_model.class.php';

$tienda_producto_model = new tienda_producto_model();
$tv_datos = $tienda_producto_model->consultar_producto_modulo_tienda($_GET['slug'] ?? '');
?>
