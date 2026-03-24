<?php
require_once __DIR__ . '/../Models/tienda_contacto_model.class.php';

$tienda_contacto_model = new tienda_contacto_model();
$tv_datos = $tienda_contacto_model->consultar_contacto_modulo_tienda();
?>
