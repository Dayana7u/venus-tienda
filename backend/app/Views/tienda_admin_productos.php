<?php
require_once __DIR__ . '/tienda_admin/tienda_admin_helper.php';

tienda_admin_render_head('Admin tienda - Productos');
tienda_admin_render_layout_inicio($tda_pagina_activa, 'Admin tienda', 'Módulo exclusivo para el registro y revisión comercial de productos.');
?>
      <section id="seccion_productos_tienda_admin" class="tda_admin_bloque">
        <div class="tda_admin_bloque_encabezado">
          <div>
            <span class="tda_admin_etiqueta">Comercial</span>
            <h3>Productos</h3>
            <p>Administra precio base, oferta, stock, resumen, descripción y la imagen principal de cada producto.</p>
          </div>
        </div>

        <div id="div_resumen_tienda_admin" class="tda_admin_mt_20"></div>
        <div id="div_resumen_ventas_tienda_admin" class="tda_admin_mt_16"></div>
      </section>

      <section class="tda_admin_bloque">
        <div class="tda_admin_dos_columnas">
          <article class="tda_admin_card_formulario">
            <h4>Crear producto</h4>
            <form id="formulario_tienda_admin_producto" class="tda_admin_formulario" enctype="multipart/form-data">
              <div class="tda_admin_grupo">
                <label for="tienda_admin_producto_categoria_id">Categoría</label>
                <select id="tienda_admin_producto_categoria_id" required></select>
              </div>
              <div class="tda_admin_grupo">
                <label for="tienda_admin_producto_codigo">Código</label>
                <input type="text" id="tienda_admin_producto_codigo" maxlength="60" required>
              </div>
              <div class="tda_admin_grupo">
                <label for="tienda_admin_producto_nombre">Nombre</label>
                <input type="text" id="tienda_admin_producto_nombre" maxlength="180" required>
              </div>
              <div class="tda_admin_grupo">
                <label for="tienda_admin_producto_etiqueta">Etiqueta</label>
                <input type="text" id="tienda_admin_producto_etiqueta" maxlength="80">
              </div>
              <div class="tda_admin_grupo">
                <label for="tienda_admin_producto_resumen">Resumen</label>
                <input type="text" id="tienda_admin_producto_resumen" maxlength="255">
              </div>
              <div class="tda_admin_grupo tda_admin_grupo_doble">
                <div>
                  <label for="tienda_admin_producto_precio_base">Precio base</label>
                  <input type="number" id="tienda_admin_producto_precio_base" min="0" step="1" required>
                </div>
                <div>
                  <label for="tienda_admin_producto_precio_oferta">Precio oferta</label>
                  <input type="number" id="tienda_admin_producto_precio_oferta" min="0" step="1" required>
                </div>
              </div>
              <div class="tda_admin_grupo tda_admin_grupo_doble">
                <div>
                  <label for="tienda_admin_producto_stock">Stock</label>
                  <input type="number" id="tienda_admin_producto_stock" min="0" step="1" required>
                </div>
                <div>
                  <label for="tienda_admin_producto_rating">Rating</label>
                  <input type="number" id="tienda_admin_producto_rating" min="0" max="5" step="0.1" required>
                </div>
              </div>
              <div class="tda_admin_grupo">
                <label for="tienda_admin_producto_descripcion">Descripción</label>
                <textarea id="tienda_admin_producto_descripcion" maxlength="1000"></textarea>
              </div>
              <div class="tda_admin_grupo">
                <label for="tienda_admin_producto_imagen_principal">Imagen principal producto</label>
                <input type="file" id="tienda_admin_producto_imagen_principal" accept="image/*">
              </div>
              <div class="tda_admin_grupo">
                <label for="tienda_admin_producto_texto_alternativo">Texto alternativo imagen</label>
                <input type="text" id="tienda_admin_producto_texto_alternativo" maxlength="180">
              </div>
              <button type="submit" id="btn_guardar_tienda_admin_producto" class="tda_btn tda_btn_principal">Guardar producto</button>
            </form>
          </article>

          <article class="tda_admin_card_listado">
            <div class="tda_admin_card_titulo_inline">
              <h4>Listado de productos</h4>
              <a href="/admin/tienda/imagenes/" class="tda_btn tda_btn_secundario">Ir a imágenes</a>
            </div>
            <div id="div_listado_tienda_admin_productos"></div>
          </article>
        </div>
      </section>
<?php tienda_admin_render_layout_fin(); ?>
