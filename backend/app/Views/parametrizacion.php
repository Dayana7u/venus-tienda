<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

require_once __DIR__ . '/../../config/configdb.php';

if (empty($_SESSION['admin_usuario_id']) || !configdb_validar_sesion_administrativa()) {
  configdb_limpiar_sesion_administrativa();
  header('Location: login.php');
  exit;
}

if (empty($_SESSION['admin_token'])) {
  $_SESSION['admin_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Parametrización administrativa</title>
  <link rel="stylesheet" href="../../public/assets/css/parametrizacion.css">
</head>
<body>
  <input type="hidden" id="token" value="<?php echo htmlspecialchars($_SESSION['admin_token'], ENT_QUOTES, 'UTF-8'); ?>">

  <main class="dx_layout">
    <aside class="dx_sidebar" id="dx_sidebar_parametrizacion">
      <div class="dx_sidebar_encabezado">
        <span class="dx_sidebar_badge">Administración</span>
        <h1>Parametrización</h1>
        <p>Gestión base del proyecto, acceso administrativo y configuración inicial.</p>
      </div>

      <div class="dx_modulos_admin">
        <a href="parametrizacion.php" class="dx_modulo_link dx_modulo_link_activo">Parametrización</a>
        <a href="seguridad.php" class="dx_modulo_link">Seguridad</a>
        <a href="/admin/tienda/" class="dx_modulo_link">Tienda</a>
      </div>

      <nav class="dx_sidebar_nav" id="dx_sidebar_nav_parametrizacion">
        <a href="#seccion_temas" data-menu-link="true">Temas</a>
        <a href="#seccion_branding" data-menu-link="true">Branding</a>
        <a href="#seccion_parametros" data-menu-link="true">Parámetros</a>
        <a href="#seccion_modulos" data-menu-link="true">Módulos</a>
        <a href="#seccion_integraciones" data-menu-link="true">Integraciones</a>
        <a href="#seccion_menus" data-menu-link="true">Menús</a>
        <a href="#seccion_roles" data-menu-link="true">Roles</a>
        <a href="#seccion_usuarios" data-menu-link="true">Usuarios</a>
      </nav>
    </aside>

    <button type="button" id="btn_menu_parametrizacion" class="dx_menu_movil" aria-label="Abrir menú">Menú</button>
    <div id="dx_sidebar_backdrop" class="dx_sidebar_backdrop dx_oculto"></div>

    <section class="dx_contenido">
      <header class="dx_header">
        <div class="dx_header_identidad">
          <p class="dx_header_etiqueta">Usuario activo</p>
          <h2><?php echo htmlspecialchars($_SESSION['admin_usuario_nombre_completo'] ?? 'Administrador', ENT_QUOTES, 'UTF-8'); ?></h2>
        </div>

        <div class="dx_header_acciones">
          <input type="text" id="buscar_parametrizacion" placeholder="Buscar registros" autocomplete="off">
          <button type="button" id="btn_recargar_parametrizacion" class="dx_btn dx_btn_secundario">Recargar</button>
          <a href="seguridad.php" class="dx_btn dx_btn_secundario">Seguridad</a>
          <a href="/admin/tienda/" class="dx_btn dx_btn_secundario">Tienda</a>
          <a href="../../cerrar_sesion.php" class="dx_btn dx_btn_principal">Cerrar sesión</a>
        </div>
      </header>

      <section id="div_mensaje_parametrizacion" class="dx_mensajes" aria-live="polite"></section>
      <section id="div_resumen_parametrizacion" class="dx_resumen"></section>

      <section id="div_secciones_parametrizacion" class="dx_secciones">
        <div id="seccion_temas"></div>
        <div id="seccion_branding"></div>
        <div id="seccion_parametros"></div>
        <div id="seccion_modulos"></div>
        <div id="seccion_integraciones"></div>
        <div id="seccion_menus"></div>
        <div id="seccion_roles"></div>
        <div id="seccion_usuarios"></div>
      </section>
    </section>
  </main>

  <section id="panel_formulario_parametrizacion" class="dx_panel_formulario dx_oculto" aria-hidden="true">
    <div class="dx_panel_backdrop" id="btn_cerrar_panel_backdrop"></div>

    <div class="dx_panel_contenido">
      <div class="dx_panel_encabezado">
        <div>
          <p class="dx_header_etiqueta" id="texto_panel_seccion">Formulario</p>
          <h3 id="titulo_panel_parametrizacion">Nuevo registro</h3>
        </div>

        <button type="button" id="btn_cerrar_panel_parametrizacion" class="dx_btn dx_btn_secundario">Cerrar</button>
      </div>

      <form id="formulario_parametrizacion" class="dx_formulario" autocomplete="off">
        <input type="hidden" id="form_seccion" name="seccion" value="">
        <input type="hidden" id="form_registro_id" name="registro_id" value="">
        <div id="div_campos_parametrizacion" class="dx_campos"></div>

        <div class="dx_formulario_acciones">
          <button type="submit" id="btn_guardar_parametrizacion" class="dx_btn dx_btn_principal">Guardar</button>
          <button type="button" id="btn_limpiar_parametrizacion" class="dx_btn dx_btn_secundario">Limpiar</button>
        </div>
      </form>
    </div>
  </section>

  <section id="modal_confirmacion_parametrizacion" class="dx_modal_confirmacion dx_oculto" aria-hidden="true">
    <div class="dx_modal_backdrop" id="btn_cancelar_confirmacion_backdrop"></div>

    <div class="dx_modal_contenido">
      <p class="dx_header_etiqueta">Confirmación</p>
      <h3 id="titulo_confirmacion_parametrizacion">Confirmar acción</h3>
      <p id="texto_confirmacion_parametrizacion">¿Desea continuar con la acción seleccionada?</p>

      <div class="dx_modal_acciones">
        <button type="button" id="btn_cancelar_confirmacion_parametrizacion" class="dx_btn dx_btn_secundario">Cancelar</button>
        <button type="button" id="btn_confirmar_parametrizacion" class="dx_btn dx_btn_principal">Confirmar</button>
      </div>
    </div>
  </section>

  <script src="../../public/assets/js/parametrizacion_template.js"></script>
  <script src="../../public/assets/js/parametrizacion_peticiones.js"></script>
  <script src="../../public/assets/js/parametrizacion.js"></script>
</body>
</html>
