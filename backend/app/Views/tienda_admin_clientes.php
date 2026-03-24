<?php
require_once __DIR__ . '/tienda_admin/tienda_admin_helper.php';

tienda_admin_render_head('Admin tienda - Clientes');
tienda_admin_render_layout_inicio($tda_pagina_activa, 'Admin tienda', 'Módulo comercial para revisar clientes registrados y su actividad de compra.');
?>
      <section class="tda_admin_bloque">
        <div class="tda_admin_bloque_encabezado">
          <div>
            <span class="tda_admin_etiqueta">Clientes</span>
            <h3>Base de clientes de la tienda</h3>
            <p>Consulta clientes, correo, teléfono, ciudad, pedidos realizados y monto acumulado.</p>
          </div>
        </div>

        <div id="div_resumen_tienda_admin" class="tda_admin_mt_20"></div>
      </section>

      <section class="tda_admin_bloque">
        <article class="tda_admin_card_listado">
          <div class="tda_admin_card_titulo_inline">
            <h4>Listado de clientes</h4>
            <a href="/admin/tienda/pedidos/" class="tda_btn tda_btn_secundario">Ir a pedidos</a>
          </div>
          <div id="div_listado_tienda_admin_clientes"></div>
        </article>
      </section>
<?php tienda_admin_render_layout_fin(); ?>
