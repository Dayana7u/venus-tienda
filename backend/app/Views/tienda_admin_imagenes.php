<?php
require_once __DIR__ . '/tienda_admin/tienda_admin_helper.php';

tienda_admin_render_head('Admin tienda - Imágenes');
tienda_admin_render_layout_inicio($tda_pagina_activa, 'Admin tienda', 'Módulo exclusivo para registrar galerías y material visual de producto.');
?>
      <section id="seccion_imagenes_tienda_admin" class="tda_admin_bloque">
        <div class="tda_admin_bloque_encabezado">
          <div>
            <span class="tda_admin_etiqueta">Visual</span>
            <h3>Imágenes</h3>
            <p>Registra imágenes adicionales por producto sin mezclar este flujo con categorías o productos.</p>
          </div>
        </div>

        <div id="div_resumen_tienda_admin" class="tda_admin_mt_20"></div>
      </section>

      <section class="tda_admin_bloque">
        <div class="tda_admin_dos_columnas">
          <article class="tda_admin_card_formulario">
            <h4>Registrar imagen</h4>
            <form id="formulario_tienda_admin_imagen" class="tda_admin_formulario" enctype="multipart/form-data">
              <div class="tda_admin_grupo">
                <label for="tienda_admin_imagen_producto_id">Producto</label>
                <select id="tienda_admin_imagen_producto_id" required></select>
              </div>
              <div class="tda_admin_grupo">
                <label for="tienda_admin_imagen_url">URL imagen</label>
                <input type="text" id="tienda_admin_imagen_url" maxlength="500">
              </div>
              <div class="tda_admin_grupo">
                <label for="tienda_admin_imagen_archivo">Archivo imagen</label>
                <input type="file" id="tienda_admin_imagen_archivo" accept="image/*">
              </div>
              <div class="tda_admin_grupo">
                <label for="tienda_admin_imagen_texto_alternativo">Texto alternativo</label>
                <input type="text" id="tienda_admin_imagen_texto_alternativo" maxlength="180">
              </div>
              <button type="submit" id="btn_guardar_tienda_admin_imagen" class="tda_btn tda_btn_principal">Guardar imagen</button>
            </form>
          </article>

          <article class="tda_admin_card_listado">
            <div class="tda_admin_card_titulo_inline">
              <h4>Listado de imágenes</h4>
              <a href="/admin/tienda/productos/" class="tda_btn tda_btn_secundario">Volver a productos</a>
            </div>
            <div id="div_listado_tienda_admin_imagenes"></div>
          </article>
        </div>
      </section>
<?php tienda_admin_render_layout_fin(); ?>
