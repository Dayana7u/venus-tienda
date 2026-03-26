<?php
require_once __DIR__ . '/tienda_admin/tienda_admin_helper.php';

tienda_admin_render_head('Admin tienda - Pedidos', $tda_tema ?? [], $tda_tema_tokens ?? [], $tda_componentes ?? []);
tienda_admin_render_layout_inicio($tda_pagina_activa, 'Admin tienda', 'Módulo comercial para revisar pedidos, estados de pago, despacho y seguimiento por cliente.', $tda_branding ?? [], $tda_tema ?? []);
?>
      <section class="tda_admin_bloque">
        <div class="tda_admin_bloque_encabezado">
          <div>
            <span class="tda_admin_etiqueta">Pedidos</span>
            <h3>Operación de pedidos</h3>
            <p>Consulta cada pedido y ejecuta acciones rápidas de operación sin salir del módulo.</p>
          </div>
        </div>
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
