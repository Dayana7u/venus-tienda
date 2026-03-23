<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

if (!empty($_SESSION['admin_usuario_id'])) {
  header('Location: parametrizacion.php');
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
  <title>Ingreso administrativo</title>
  <link rel="stylesheet" href="../../public/assets/css/login.css">
</head>
<body>
  <input type="hidden" id="token" value="<?php echo htmlspecialchars($_SESSION['admin_token'], ENT_QUOTES, 'UTF-8'); ?>">

  <main class="dx_login">
    <section class="dx_login_panel">
      <div class="dx_login_columna dx_login_columna_info">
        <span class="dx_login_badge">Panel administrativo</span>
        <h1>Ingreso</h1>
        <p>Acceso independiente para la administración de parametrización, usuarios y seguridad inicial.</p>
      </div>

      <div class="dx_login_columna dx_login_columna_formulario">
        <div class="dx_login_encabezado_formulario">
          <p class="dx_login_etiqueta">Inicio de sesión</p>
          <h2>Continuar</h2>
        </div>

        <div id="div_mensaje_login" class="dx_login_mensaje" aria-live="polite"></div>

        <form id="formulario_login" class="dx_login_formulario" autocomplete="off">
          <div class="dx_login_grupo">
            <label for="login_usuario">Usuario</label>
            <input
              type="text"
              id="login_usuario"
              name="login_usuario"
              placeholder="Ingrese el usuario"
              maxlength="60"
              required
            >
          </div>

          <div class="dx_login_grupo">
            <label for="login_clave">Clave</label>
            <div class="dx_login_campo_clave">
              <input
                type="password"
                id="login_clave"
                name="login_clave"
                placeholder="Ingrese la clave"
                maxlength="120"
                required
              >

              <button type="button" id="btn_ver_clave_login" class="dx_btn_icono" aria-label="Mostrar u ocultar clave">Ver</button>
            </div>
          </div>

          <div class="dx_login_acciones">
            <button type="submit" id="btn_ingresar_login" class="dx_btn dx_btn_principal">Ingresar</button>
            <button type="button" id="btn_limpiar_login" class="dx_btn dx_btn_secundario">Limpiar</button>
          </div>
        </form>

        <div id="div_resumen_login" class="dx_login_resumen"></div>
      </div>
    </section>
  </main>

  <script src="../../public/assets/js/login_template.js"></script>
  <script src="../../public/assets/js/login_peticiones.js"></script>
  <script src="../../public/assets/js/login.js"></script>
</body>
</html>
