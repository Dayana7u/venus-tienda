<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

if (empty($_SESSION['token'])) {
  $_SESSION['token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Parametrización</title>
</head>
<body>
  <input type="hidden" id="token" value="<?php echo htmlspecialchars($_SESSION['token'], ENT_QUOTES, 'UTF-8'); ?>">

  <main id="modulo_parametrizacion">
    <section>
      <h1>Parametrización</h1>
      <p>Administración base del esquema system.</p>
    </section>

    <section>
      <label for="buscar_parametrizacion">Buscar</label>
      <input
        type="text"
        id="buscar_parametrizacion"
        name="buscar_parametrizacion"
        placeholder="Filtrar registros"
        autocomplete="off"
      >
      <button type="button" id="btn_recargar_parametrizacion">Recargar</button>
    </section>

    <section>
      <article>
        <h2>Temas</h2>
        <div id="div_contenido_temas_parametrizacion"></div>
      </article>

      <article>
        <h2>Branding</h2>
        <div id="div_contenido_branding_parametrizacion"></div>
      </article>

      <article>
        <h2>Parámetros</h2>
        <div id="div_contenido_parametros_parametrizacion"></div>
      </article>

      <article>
        <h2>Módulos</h2>
        <div id="div_contenido_modulos_parametrizacion"></div>
      </article>

      <article>
        <h2>Integraciones</h2>
        <div id="div_contenido_integraciones_parametrizacion"></div>
      </article>

      <article>
        <h2>Menús</h2>
        <div id="div_contenido_menus_parametrizacion"></div>
      </article>
    </section>
  </main>

  <script src="../../public/assets/js/parametrizacion_peticiones.js"></script>
  <script src="../../public/assets/js/parametrizacion.js"></script>
</body>
</html>
