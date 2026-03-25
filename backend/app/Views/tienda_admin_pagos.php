<?php
require_once __DIR__ . '/tienda_admin/tienda_admin_helper.php';

tienda_admin_render_head('Admin tienda - Pagos', $tda_tema ?? []);
tienda_admin_render_layout_inicio($tda_pagina_activa, 'Admin tienda', 'Módulo comercial para revisar transacciones registradas, referencias y estados del pago.', $tda_branding ?? [], $tda_tema ?? []);
?>
      <section class="tda_admin_bloque">
        <div class="tda_admin_bloque_encabezado">
          <div>
            <span class="tda_admin_etiqueta">Pagos</span>
            <h3>Seguimiento de pagos</h3>
            <p>Consulta la referencia registrada por checkout, método de pago, estado y relación con el pedido.</p>
          </div>
        </div>
      </section>

      <section class="tda_admin_bloque">
        <article class="tda_admin_card_listado">
          <div class="tda_admin_card_titulo_inline">
            <h4>Pagos registrados</h4>
            <a href="/admin/tienda/ventas/" class="tda_btn tda_btn_secundario">Ir a ventas</a>
          </div>
          <div id="div_listado_tienda_admin_pagos"></div>
        </article>
      </section>
<?php tienda_admin_render_layout_fin(); ?>
