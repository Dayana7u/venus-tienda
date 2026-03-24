<?php
require_once __DIR__ . '/tienda_admin/tienda_admin_helper.php';

tienda_admin_render_head('Admin tienda - Pedidos');
tienda_admin_render_layout_inicio($tda_pagina_activa, 'Admin tienda', 'Módulo comercial para revisar pedidos, estados, pagos y líneas de venta.');
?>
      <section class="tda_admin_bloque">
        <div class="tda_admin_bloque_encabezado">
          <div>
            <span class="tda_admin_etiqueta">Pedidos</span>
            <h3>Operación de pedidos</h3>
            <p>Revisa los pedidos creados, el estado del pago, la entrega y el total de cada compra.</p>
          </div>
        </div>

        <div id="div_metricas_tienda_admin" class="tda_admin_mt_20"></div>
        <div id="div_resumen_tienda_admin" class="tda_admin_mt_20"></div>
        <div id="div_resumen_ventas_tienda_admin" class="tda_admin_mt_16"></div>
      </section>

      <section class="tda_admin_bloque">
        <article class="tda_admin_card_listado">
          <div class="tda_admin_card_titulo_inline">
            <h4>Listado de pedidos</h4>
            <a href="/admin/tienda/ventas/" class="tda_btn tda_btn_secundario">Ir a ventas</a>
          </div>
          <div id="div_listado_tienda_admin_pedidos"></div>
        </article>
      </section>
<?php tienda_admin_render_layout_fin(); ?>
