<?php
require_once __DIR__ . '/../Models/tienda_checkout_model.class.php';

$tienda_checkout_model = new tienda_checkout_model();
$es_ajax = (
  ($_POST['ajax'] ?? $_GET['ajax'] ?? '') === '1'
  || strpos((string) ($_SERVER['HTTP_ACCEPT'] ?? ''), 'application/json') !== false
);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $accion = trim((string) ($_POST['accion'] ?? ''));
  $respuesta = [
    'estado'  => false,
    'mensaje' => 'Acción no válida.',
    'datos'   => [],
  ];

  switch ($accion) {
    case 'guardar_checkout_pago':
      $respuesta = $tienda_checkout_model->guardar_checkout_pago_tienda();
      break;
  }

  if ($es_ajax === true) {
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode($respuesta);
    exit;
  }

  if (($respuesta['estado'] ?? false) === true) {
    header('Location: ' . ($respuesta['datos']['redirect'] ?? '/checkout/pago/'));
    exit;
  }

  $_SESSION['tv_mensaje'] = $respuesta['mensaje'] ?? 'No fue posible continuar con el pago.';
  header('Location: /checkout/pago/');
  exit;
}

$tv_datos = $tienda_checkout_model->consultar_checkout_pago_modulo_tienda();
?>
