<?php
require_once __DIR__ . '/tienda_catalogo_base_model.class.php';

class tienda_catalogo_model extends tienda_catalogo_base_model {
  public function consultar_catalogo_modulo_tienda($linea = '', $buscar = '') {
    $contexto = $this->consultar_contexto_tienda();

    return [
      'contexto'   => $contexto,
      'productos'  => $this->consultar_catalogo_tienda($linea, $buscar),
      'lineas'     => $this->consultar_lineas_tienda(),
      'filtros'    => [
        'linea'  => $linea,
        'buscar' => $buscar,
      ],
      'carrito'    => $this->consultar_carrito_tienda(),
    ];
  }
}
?>
