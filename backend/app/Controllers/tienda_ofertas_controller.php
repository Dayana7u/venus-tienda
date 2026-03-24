<?php
require_once __DIR__ . '/../Models/tienda_ofertas_model.class.php';

$tienda_ofertas_model = new tienda_ofertas_model();
$tv_datos = $tienda_ofertas_model->consultar_ofertas_modulo_tienda();
?>
