<?php
require_once __DIR__ . '/tienda_admin/tienda_admin_helper.php';

tienda_admin_render_head('Admin tienda - Auditoría');
tienda_admin_render_layout_inicio($tda_pagina_activa, 'Admin tienda', 'Módulo para revisar trazabilidad de cambios ejecutados en el panel comercial de la tienda.');
?>
      <section class="tda_admin_bloque">
        <div class="tda_admin_bloque_encabezado">
          <div>
            <span class="tda_admin_etiqueta">Auditoría</span>
            <h3>Trazabilidad operativa</h3>
            <p>Consulta usuario, acción, módulo y hora del cambio sin salir del panel tienda.</p>
          </div>
        </div>
      </section>

      <section class="tda_admin_bloque">
        <article class="tda_admin_card_listado">
          <div class="tda_admin_card_titulo_inline">
            <h4>Listado de auditoría</h4>
            <a href="/admin/tienda/dashboard/" class="tda_btn tda_btn_secundario">Ir a dashboard</a>
          </div>
          <div id="div_listado_tienda_admin_auditoria"></div>
        </article>
      </section>
<?php tienda_admin_render_layout_fin(); ?>
