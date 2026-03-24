<?php
require_once __DIR__ . '/tienda_catalogo_base_model.class.php';

class tienda_contacto_model extends tienda_catalogo_base_model {
  public function consultar_contacto_modulo_tienda() {
    return [
      'contexto'  => $this->consultar_contexto_tienda(),
      'canales'   => [
        ['titulo' => 'Asesoría personalizada', 'descripcion' => 'Acompañamiento para escoger maquillaje, skincare y kits según objetivo de compra.'],
        ['titulo' => 'Atención por WhatsApp', 'descripcion' => 'Canal pensado para resolver disponibilidad, tiempos de entrega y combinaciones de productos.'],
        ['titulo' => 'Compras y regalos', 'descripcion' => 'Apoyo para campañas, regalos y selección de detalles visuales para fechas especiales.'],
      ],
      'carrito'   => $this->consultar_carrito_tienda(),
    ];
  }
}
?>
