<?php
require_once __DIR__ . '/tienda_admin/tienda_admin_helper.php';

$tda_permiso_formulario = tienda_admin_usuario_tiene_permiso('TIENDA_CATEGORIAS_GUARDAR') || tienda_admin_usuario_tiene_permiso('TIENDA_CATEGORIAS_EDITAR');

tienda_admin_render_head('Admin tienda - Categorías', $tda_tema ?? [], $tda_tema_tokens ?? [], $tda_componentes ?? []);
tienda_admin_render_layout_inicio($tda_pagina_activa, 'Admin tienda', 'Módulo para crear, editar, inactivar y organizar categorías visibles del catálogo comercial.', $tda_branding ?? [], $tda_tema ?? []);
?>
      <section id="seccion_categorias_tienda_admin" class="tda_admin_bloque">
        <div class="tda_admin_bloque_encabezado">
          <div>
            <span class="tda_admin_etiqueta">Catálogo</span>
            <h3>Categorías</h3>
            <p>Define las categorías visibles de la tienda con imagen, orden, línea comercial y descripción corta.</p>
          </div>
        </div>
      </section>

      <section class="tda_admin_bloque">
        <div class="tda_admin_dos_columnas">
          <?php if ($tda_permiso_formulario) { ?>
          <article class="tda_admin_card_formulario">
            <div class="tda_admin_card_titulo_inline">
              <h4 id="titulo_formulario_tienda_admin_categoria">Crear categoría</h4>
              <button type="button" id="btn_cancelar_edicion_tienda_admin_categoria" class="tda_btn tda_btn_secundario tda_admin_btn_oculto">Cancelar</button>
            </div>
            <form id="formulario_tienda_admin_categoria" class="tda_admin_formulario" enctype="multipart/form-data">
              <input type="hidden" id="tienda_admin_categoria_id">
              <div class="tda_admin_grupo">
                <label for="tienda_admin_categoria_codigo" class="tda_admin_label_obligatorio">Código</label>
                <input type="text" id="tienda_admin_categoria_codigo" maxlength="60" required>
              </div>
              <div class="tda_admin_grupo">
                <label for="tienda_admin_categoria_nombre" class="tda_admin_label_obligatorio">Nombre</label>
                <input type="text" id="tienda_admin_categoria_nombre" maxlength="150" required>
              </div>
              <div class="tda_admin_grupo">
                <label for="tienda_admin_categoria_linea" class="tda_admin_label_obligatorio">Línea</label>
                <select id="tienda_admin_categoria_linea" required>
                  <option value="">Seleccione</option>
                  <option value="maquillaje">Maquillaje</option>
                  <option value="skincare">Skincare</option>
                  <option value="accesorios">Accesorios</option>
                  <option value="cabello">Cabello</option>
                </select>
              </div>
              <div class="tda_admin_grupo">
                <label for="tienda_admin_categoria_descripcion" class="tda_admin_label_obligatorio">Descripción</label>
                <textarea id="tienda_admin_categoria_descripcion" maxlength="400" required></textarea>
              </div>
              <div class="tda_admin_grupo">
                <label for="tienda_admin_categoria_imagen" class="tda_admin_label_obligatorio">Imagen categoría</label>
                <input type="file" id="tienda_admin_categoria_imagen" accept="image/*">
              </div>
              <div class="tda_admin_grupo">
                <label for="tienda_admin_categoria_texto_alternativo" class="tda_admin_label_obligatorio">Texto alternativo imagen</label>
                <input type="text" id="tienda_admin_categoria_texto_alternativo" maxlength="180" required>
              </div>
              <button type="submit" id="btn_guardar_tienda_admin_categoria" class="tda_btn tda_btn_principal">Guardar categoría</button>
            </form>
          </article>
          <?php } ?>

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
