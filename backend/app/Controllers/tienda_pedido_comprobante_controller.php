<?php
require_once __DIR__ . '/../Models/tienda_pedido_comprobante_model.class.php';

$tienda_pedido_comprobante_model = new tienda_pedido_comprobante_model();
$tv_datos = $tienda_pedido_comprobante_model->consultar_pedido_comprobante_modulo_tienda();
?>
