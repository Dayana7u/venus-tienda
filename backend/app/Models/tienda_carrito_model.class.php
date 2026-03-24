<?php
require_once __DIR__ . '/tienda_catalogo_base_model.class.php';

class tienda_carrito_model extends tienda_catalogo_base_model {
  public function consultar_carrito_modulo_tienda() {
    return [
      'contexto' => $this->consultar_contexto_tienda(),
      'carrito'  => $this->consultar_carrito_tienda(),
    ];
  }
}
?>
