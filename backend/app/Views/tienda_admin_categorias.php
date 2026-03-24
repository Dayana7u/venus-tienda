<?php
require_once __DIR__ . '/tienda_admin/tienda_admin_helper.php';

tienda_admin_render_head('Admin tienda - Categorías');
tienda_admin_render_layout_inicio($tda_pagina_activa, 'Admin tienda', 'Módulo exclusivo para crear, visualizar y organizar categorías del catálogo.');
?>
      <section id="seccion_categorias_tienda_admin" class="tda_admin_bloque">
        <div class="tda_admin_bloque_encabezado">
          <div>
            <span class="tda_admin_etiqueta">Catálogo</span>
            <h3>Categorías</h3>
            <p>Registra líneas visuales y comerciales para maquillaje, skincare, accesorios y demás segmentos de la tienda.</p>
          </div>
        </div>

        <div id="div_resumen_tienda_admin" class="tda_admin_mt_20"></div>
      </section>

      <section class="tda_admin_bloque">
        <div class="tda_admin_dos_columnas">
          <article class="tda_admin_card_formulario">
            <h4>Crear categoría</h4>
            <form id="formulario_tienda_admin_categoria" class="tda_admin_formulario" enctype="multipart/form-data">
              <div class="tda_admin_grupo">
                <label for="tienda_admin_categoria_codigo">Código</label>
                <input type="text" id="tienda_admin_categoria_codigo" maxlength="60" required>
              </div>
              <div class="tda_admin_grupo">
                <label for="tienda_admin_categoria_nombre">Nombre</label>
                <input type="text" id="tienda_admin_categoria_nombre" maxlength="150" required>
              </div>
              <div class="tda_admin_grupo">
                <label for="tienda_admin_categoria_linea">Línea</label>
                <select id="tienda_admin_categoria_linea" required>
                  <option value="">Seleccione</option>
                  <option value="maquillaje">Maquillaje</option>
                  <option value="skincare">Skincare</option>
                  <option value="accesorios">Accesorios</option>
                  <option value="cabello">Cabello</option>
                </select>
              </div>
              <div class="tda_admin_grupo">
                <label for="tienda_admin_categoria_descripcion">Descripción</label>
                <textarea id="tienda_admin_categoria_descripcion" maxlength="400"></textarea>
              </div>
              <div class="tda_admin_grupo">
                <label for="tienda_admin_categoria_imagen">Imagen categoría</label>
                <input type="file" id="tienda_admin_categoria_imagen" accept="image/*">
              </div>
              <div class="tda_admin_grupo">
                <label for="tienda_admin_categoria_texto_alternativo">Texto alternativo imagen</label>
                <input type="text" id="tienda_admin_categoria_texto_alternativo" maxlength="180">
              </div>
              <button type="submit" id="btn_guardar_tienda_admin_categoria" class="tda_btn tda_btn_principal">Guardar categoría</button>
            </form>
          </article>

          <article class="tda_admin_card_listado">
            <div class="tda_admin_card_titulo_inline">
              <h4>Listado de categorías</h4>
              <a href="/admin/tienda/productos/" class="tda_btn tda_btn_secundario">Ir a productos</a>
            </div>
            <div id="div_listado_tienda_admin_categorias"></div>
          </article>
        </div>
      </section>
<?php tienda_admin_render_layout_fin(); ?>
