<?php
require_once __DIR__ . '/../Models/tienda_inicio_model.class.php';

$tienda_inicio_model = new tienda_inicio_model();
$tv_datos = $tienda_inicio_model->consultar_inicio_tienda();
?>
