<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

if (empty($_SESSION['tienda_publica_token'])) {
  $_SESSION['tienda_publica_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tienda pública</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../../public/assets/css/tienda_publica.css">
</head>
<body>
  <input type="hidden" id="token" value="<?php echo htmlspecialchars($_SESSION['tienda_publica_token'], ENT_QUOTES, 'UTF-8'); ?>">

  <main class="tv_tienda">
    <section class="tv_topbar" id="tv_topbar_tienda_publica">
      <div id="div_topbar_tienda_publica"></div>
    </section>

    <header class="tv_header" id="tv_header_tienda_publica">
      <div class="tv_header_superior">
        <a href="#seccion_inicio_publico" id="tv_logo_tienda_publica" class="tv_logo">Tienda</a>

        <button type="button" id="btn_menu_tienda_publica" class="tv_btn_menu" aria-label="Abrir menú">
          Menú
        </button>

        <nav class="tv_nav" id="tv_nav_tienda_publica"></nav>

        <div class="tv_header_acciones" id="tv_header_acciones_tienda_publica">
          <div id="div_buscador_tienda_publica" class="tv_buscador"></div>
          <div id="div_carrito_tienda_publica" class="tv_carrito"></div>
        </div>
      </div>
    </header>

    <div id="div_backdrop_tienda_publica" class="tv_backdrop tv_oculto"></div>

    <section id="div_mensaje_tienda_publica" class="tv_mensajes" aria-live="polite"></section>

    <section id="seccion_inicio_publico" class="tv_hero">
      <div id="div_hero_tienda_publica"></div>
    </section>

    <section class="tv_bloque tv_bloque_beneficios">
      <div id="div_beneficios_tienda_publica"></div>
    </section>

    <section class="tv_bloque tv_bloque_suave">
      <div id="div_colecciones_tienda_publica"></div>
    </section>

    <section class="tv_bloque">
      <div id="div_lineas_tienda_publica"></div>
    </section>

    <section id="seccion_catalogo_publico" class="tv_bloque">
      <div id="div_categorias_tienda_publica"></div>
    </section>

    <section class="tv_bloque tv_bloque_suave">
      <div id="div_destacados_tienda_publica"></div>
    </section>

    <section class="tv_bloque">
      <div id="div_mas_vendidos_tienda_publica"></div>
    </section>

    <section class="tv_bloque tv_bloque_suave">
      <div id="div_rutina_tienda_publica"></div>
    </section>

    <section id="seccion_ofertas_publico" class="tv_bloque tv_bloque_suave">
      <div id="div_ofertas_tienda_publica"></div>
    </section>

    <section class="tv_bloque">
      <div id="div_testimonios_tienda_publica"></div>
    </section>

    <section class="tv_bloque tv_bloque_suave">
      <div id="div_faq_tienda_publica"></div>
    </section>

    <section id="seccion_contacto_publico" class="tv_bloque tv_bloque_contacto">
      <div id="div_contacto_tienda_publica"></div>
    </section>

    <section class="tv_bloque tv_bloque_newsletter">
      <div id="div_newsletter_tienda_publica"></div>
    </section>

    <footer class="tv_footer" id="tv_footer_tienda_publica">
      <div id="div_footer_tienda_publica"></div>
    </footer>
  </main>

  <script src="../../public/assets/js/tienda_publica_template.js"></script>
  <script src="../../public/assets/js/tienda_publica_peticiones.js"></script>
  <script src="../../public/assets/js/tienda_publica.js"></script>
</body>
</html>
