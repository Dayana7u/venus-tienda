<?php
require_once __DIR__ . '/tienda_admin/tienda_admin_helper.php';

tienda_admin_render_head('Admin tienda - Ventas');
tienda_admin_render_layout_inicio($tda_pagina_activa, 'Admin tienda', 'Módulo comercial para ingresos, ticket promedio, descuentos y productos con mayor salida.');
?>
      <section class="tda_admin_bloque">
        <div class="tda_admin_bloque_encabezado">
          <div>
            <span class="tda_admin_etiqueta">Ventas</span>
            <h3>Resumen de ingresos</h3>
            <p>Indicadores comerciales para entender la operación de la tienda y el rendimiento del catálogo.</p>
          </div>
        </div>

        <div id="div_metricas_tienda_admin" class="tda_admin_mt_20"></div>
        <div id="div_paneles_tienda_admin"></div>
        <div id="div_resumen_ventas_tienda_admin" class="tda_admin_mt_16"></div>
      </section>

      <section class="tda_admin_bloque tda_admin_dos_columnas_resumen">
        <article class="tda_admin_card_listado">
          <div class="tda_admin_card_titulo_inline">
            <h4>Productos top</h4>
            <a href="/admin/tienda/productos/" class="tda_btn tda_btn_secundario">Ir a productos</a>
          </div>
          <div id="div_listado_tienda_admin_productos_top"></div>
        </article>

        <article class="tda_admin_card_listado">
          <div class="tda_admin_card_titulo_inline">
            <h4>Pedidos recientes</h4>
            <a href="/admin/tienda/pedidos/" class="tda_btn tda_btn_secundario">Ver pedidos</a>
          </div>
          <div id="div_listado_tienda_admin_pedidos"></div>
        </article>
      </section>
<?php tienda_admin_render_layout_fin(); ?>
