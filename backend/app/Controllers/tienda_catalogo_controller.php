<?php
require_once __DIR__ . '/../Models/tienda_catalogo_model.class.php';

$tienda_catalogo_model = new tienda_catalogo_model();
$tv_datos = $tienda_catalogo_model->consultar_catalogo_modulo_tienda($_GET['linea'] ?? '', $_GET['buscar'] ?? '');
?>
