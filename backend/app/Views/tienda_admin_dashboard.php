<?php
require_once __DIR__ . '/tienda_admin/tienda_admin_helper.php';
$tda_resumen = $tda_datos['resumen'] ?? [];
$tda_ventas  = $tda_datos['ventas'] ?? [];

tienda_admin_render_head('Admin tienda - Dashboard');
tienda_admin_render_layout_inicio($tda_pagina_activa, 'Admin tienda', 'Panel comercial de la tienda con navegación por módulos, indicadores, pedidos, clientes y catálogo.');
?>
      <section class="tda_admin_bloque">
        <div class="tda_admin_bloque_encabezado">
          <div>
            <span class="tda_admin_etiqueta">Dashboard</span>
            <h3>Resumen general de la tienda</h3>
            <p>Vista principal para revisar catálogo, pedidos, clientes, ingresos y accesos rápidos del panel comercial.</p>
          </div>
        </div>

        <div id="div_metricas_tienda_admin" class="tda_admin_mt_20"></div>
        <div id="div_paneles_tienda_admin"></div>
      </section>

      <section class="tda_admin_bloque">
        <div class="tda_admin_card_listado">
          <div class="tda_admin_card_titulo_inline">
            <h4>Indicadores base</h4>
            <a href="/admin/tienda/ventas/" class="tda_btn tda_btn_secundario">Ver ventas</a>
          </div>
          <div id="div_resumen_tienda_admin"></div>
          <div id="div_resumen_ventas_tienda_admin" class="tda_admin_mt_16"></div>
        </div>
      </section>

      <section class="tda_admin_bloque">
        <div class="tda_admin_modulos_grid">
          <a href="/admin/tienda/pedidos/" class="tda_admin_modulo_card">
            <span class="tda_admin_etiqueta">Operación</span>
            <h4>Pedidos</h4>
            <p>Revisa estados, montos, cantidades y detalle comercial de cada venta.</p>
            <strong><?php echo (int) ($tda_resumen['pedidos'] ?? 0); ?> pedidos</strong>
          </a>
          <a href="/admin/tienda/clientes/" class="tda_admin_modulo_card">
            <span class="tda_admin_etiqueta">Relación</span>
            <h4>Clientes</h4>
            <p>Consulta clientes registrados, contacto, dirección principal y actividad de compra.</p>
            <strong><?php echo (int) ($tda_resumen['clientes'] ?? 0); ?> clientes</strong>
          </a>
          <a href="/admin/tienda/ventas/" class="tda_admin_modulo_card">
            <span class="tda_admin_etiqueta">Ventas</span>
            <h4>Ingresos</h4>
            <p>Monitorea total vendido, ticket promedio, descuentos y productos top.</p>
            <strong><?php echo '$' . number_format((float) ($tda_ventas['total_ingresos'] ?? 0), 0, ',', '.'); ?></strong>
          </a>
          <a href="/admin/tienda/productos/" class="tda_admin_modulo_card">
            <span class="tda_admin_etiqueta">Catálogo</span>
            <h4>Productos</h4>
            <p>Administra precios, stock, oferta, imagen principal y posición comercial.</p>
            <strong><?php echo (int) ($tda_resumen['productos'] ?? 0); ?> productos</strong>
          </a>
        </div>
      </section>

      <section class="tda_admin_bloque tda_admin_dos_columnas_resumen">
        <article class="tda_admin_card_listado">
          <div class="tda_admin_card_titulo_inline">
            <h4>Pedidos recientes</h4>
            <a href="/admin/tienda/pedidos/" class="tda_btn tda_btn_secundario">Ver pedidos</a>
          </div>
          <div id="div_listado_tienda_admin_pedidos"></div>
        </article>

        <article class="tda_admin_card_listado">
          <div class="tda_admin_card_titulo_inline">
            <h4>Clientes recientes</h4>
            <a href="/admin/tienda/clientes/" class="tda_btn tda_btn_secundario">Ver clientes</a>
          </div>
          <div id="div_listado_tienda_admin_clientes"></div>
        </article>
      </section>

      <section class="tda_admin_bloque">
        <div class="tda_admin_card_listado">
          <div class="tda_admin_card_titulo_inline">
            <h4>Productos con mejor salida</h4>
            <a href="/admin/tienda/ventas/" class="tda_btn tda_btn_secundario">Ver ventas</a>
          </div>
          <div id="div_listado_tienda_admin_productos_top"></div>
        </div>
      </section>
<?php tienda_admin_render_layout_fin(); ?>
