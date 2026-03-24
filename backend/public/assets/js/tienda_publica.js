let tpub = {};
// Datos generales
  tpub.token                     = document.getElementById(`token`).value;
  tpub.temporizador_alerta       = null;
  tpub.branding                  = {};
  tpub.modulo                    = {};
  tpub.menus                     = [];
  tpub.tema                      = {};
  tpub.tema_tokens               = {};
  tpub.componentes               = {};
  tpub.parametros                = {};
// Botones
  tpub.btn_menu_tienda_publica   = document.getElementById(`btn_menu_tienda_publica`);
// Divs
  tpub.div_topbar_tienda_publica     = document.getElementById(`div_topbar_tienda_publica`);
  tpub.tv_nav_tienda_publica         = document.getElementById(`tv_nav_tienda_publica`);
  tpub.div_backdrop_tienda_publica   = document.getElementById(`div_backdrop_tienda_publica`);
  tpub.div_mensaje_tienda_publica    = document.getElementById(`div_mensaje_tienda_publica`);
  tpub.div_hero_tienda_publica       = document.getElementById(`div_hero_tienda_publica`);
  tpub.div_beneficios_tienda_publica = document.getElementById(`div_beneficios_tienda_publica`);
  tpub.div_categorias_tienda_publica = document.getElementById(`div_categorias_tienda_publica`);
  tpub.div_destacados_tienda_publica = document.getElementById(`div_destacados_tienda_publica`);
  tpub.div_rutina_tienda_publica     = document.getElementById(`div_rutina_tienda_publica`);
  tpub.div_ofertas_tienda_publica    = document.getElementById(`div_ofertas_tienda_publica`);
  tpub.div_testimonios_tienda_publica = document.getElementById(`div_testimonios_tienda_publica`);
  tpub.div_contacto_tienda_publica   = document.getElementById(`div_contacto_tienda_publica`);
  tpub.div_footer_tienda_publica     = document.getElementById(`div_footer_tienda_publica`);
  tpub.div_buscador_tienda_publica   = document.getElementById(`div_buscador_tienda_publica`);
  tpub.div_carrito_tienda_publica    = document.getElementById(`div_carrito_tienda_publica`);
  tpub.tv_topbar_tienda_publica      = document.getElementById(`tv_topbar_tienda_publica`);
  tpub.tv_header_tienda_publica      = document.getElementById(`tv_header_tienda_publica`);
  tpub.tv_footer_tienda_publica      = document.getElementById(`tv_footer_tienda_publica`);
  tpub.tv_logo_tienda_publica        = document.getElementById(`tv_logo_tienda_publica`);

document.addEventListener(`DOMContentLoaded`, async function() {
  await inicializar_tienda_publica();
});
/**
 * Función encargada de inicializar el frente público de la tienda.
 */
async function inicializar_tienda_publica() {
  eventos_tienda_publica();
  await tienda_publica_inicializar_peticiones(tpub.token);
  await listar_portada_tienda_publica();
}
/**
 * Función encargada de registrar eventos del frente público.
 */
function eventos_tienda_publica() {
  tpub.btn_menu_tienda_publica.addEventListener(`click`, function() {
    alternar_menu_tienda_publica(true);
  });

  tpub.div_backdrop_tienda_publica.addEventListener(`click`, function() {
    alternar_menu_tienda_publica(false);
  });

  tpub.div_mensaje_tienda_publica.addEventListener(`click`, function(event) {
    if (event.target.matches(`[data-alerta-cerrar="true"]`)) {
      limpiar_alerta_tienda_publica();
    }
  });
}
/**
 * Función encargada de consultar y renderizar la portada pública.
 */
async function listar_portada_tienda_publica() {
  let petición = await tienda_publica_listar_portada_peticiones(tpub.token);

  if (petición.estado !== true) {
    mostrar_alerta_tienda_publica(`error`, petición.mensaje);
    return;
  }

  tpub.branding    = petición.datos.branding || {};
  tpub.modulo      = petición.datos.modulo || {};
  tpub.menus       = petición.datos.menus || [];
  tpub.tema        = petición.datos.tema || {};
  tpub.tema_tokens = petición.datos.tema_tokens || {};
  tpub.componentes = petición.datos.componentes || {};
  tpub.parametros  = petición.datos.parametros || {};

  aplicar_tema_tienda_publica(tpub.tema_tokens, tpub.componentes);
  renderizar_portada_tienda_publica();
}
/**
 * Función encargada de renderizar el contenido del frente público.
 */
function renderizar_portada_tienda_publica() {
  tpub.tv_logo_tienda_publica.textContent         = tpub.branding.nombre_comercial || tpub.parametros[`app.nombre`] || `Tienda`;
  tpub.div_topbar_tienda_publica.innerHTML        = template_topbar_tienda_publica(tpub.modulo);
  tpub.tv_nav_tienda_publica.innerHTML            = template_menu_tienda_publica(tpub.menus);
  tpub.div_buscador_tienda_publica.innerHTML      = template_buscador_tienda_publica(tpub.modulo);
  tpub.div_carrito_tienda_publica.innerHTML       = template_carrito_tienda_publica(tpub.modulo);
  tpub.div_hero_tienda_publica.innerHTML          = template_hero_tienda_publica(tpub.branding, tpub.modulo, tpub.parametros);
  tpub.div_beneficios_tienda_publica.innerHTML    = template_beneficios_tienda_publica(tpub.modulo);
  tpub.div_categorias_tienda_publica.innerHTML    = template_categorias_tienda_publica(tpub.modulo);
  tpub.div_destacados_tienda_publica.innerHTML    = template_destacados_tienda_publica(tpub.modulo);
  tpub.div_rutina_tienda_publica.innerHTML        = template_rutina_tienda_publica(tpub.modulo);
  tpub.div_ofertas_tienda_publica.innerHTML       = template_ofertas_tienda_publica(tpub.modulo);
  tpub.div_testimonios_tienda_publica.innerHTML   = template_testimonios_tienda_publica(tpub.modulo);
  tpub.div_contacto_tienda_publica.innerHTML      = template_contacto_tienda_publica(tpub.branding, tpub.modulo);
  tpub.div_footer_tienda_publica.innerHTML        = template_footer_tienda_publica(tpub.branding, tpub.menus);

  document.querySelectorAll(`.tv_nav_link`).forEach(function(enlace) {
    enlace.addEventListener(`click`, function() {
      if (window.innerWidth <= 960) {
        alternar_menu_tienda_publica(false);
      }
    });
  });
}
/**
 * Función encargada de aplicar las variables visuales del tema activo.
 *
 * @param      object  tema_tokens  Tokens configurados por tema.
 * @param      object  componentes  Configuración visual por componente.
 */
function aplicar_tema_tienda_publica(tema_tokens, componentes) {
  const raiz = document.documentElement;
  const banner_principal = componentes[`banner.principal`] || {};
  const button_primary   = componentes[`button.primary`] || {};
  const card_product     = componentes[`card.product`] || {};
  const input_search     = componentes[`input.search`] || {};
  const badge            = componentes[`badge`] || {};
  const footer           = componentes[`footer`] || {};
  const topbar           = componentes[`topbar`] || {};
  const hero_panel       = componentes[`hero.panel`] || {};
  const section_soft     = componentes[`section.soft`] || {};
  const product_price    = componentes[`product.price`] || {};
  const product_media    = componentes[`product.media`] || {};
  const testimonial      = componentes[`testimonial`] || {};

  raiz.style.setProperty(`--tv-color-primary`, tema_tokens[`color.primary`] || `#111111`);
  raiz.style.setProperty(`--tv-color-secondary`, tema_tokens[`color.secondary`] || `#f5f5f5`);
  raiz.style.setProperty(`--tv-color-accent`, tema_tokens[`color.accent`] || `#111111`);
  raiz.style.setProperty(`--tv-color-background`, tema_tokens[`color.background`] || `#ffffff`);
  raiz.style.setProperty(`--tv-color-surface`, tema_tokens[`color.surface`] || `#ffffff`);
  raiz.style.setProperty(`--tv-color-text`, tema_tokens[`color.text`] || `#222222`);
  raiz.style.setProperty(`--tv-color-text-soft`, tema_tokens[`color.text.soft`] || `#666666`);
  raiz.style.setProperty(`--tv-color-border`, tema_tokens[`color.border`] || `#dddddd`);
  raiz.style.setProperty(`--tv-font-heading`, tema_tokens[`font.family.heading`] || `Playfair Display`);
  raiz.style.setProperty(`--tv-font-base`, tema_tokens[`font.family.body`] || tema_tokens[`font.family.base`] || `Poppins`);
  raiz.style.setProperty(`--tv-font-size-base`, tema_tokens[`font.size.base`] || `16px`);
  raiz.style.setProperty(`--tv-radius-sm`, tema_tokens[`border.radius.sm`] || `8px`);
  raiz.style.setProperty(`--tv-radius-md`, tema_tokens[`border.radius.md`] || `16px`);
  raiz.style.setProperty(`--tv-radius-lg`, tema_tokens[`border.radius.lg`] || `24px`);
  raiz.style.setProperty(`--tv-banner-background`, banner_principal[`background_color`] || tema_tokens[`color.secondary`] || `#f5f5f5`);
  raiz.style.setProperty(`--tv-banner-radius`, banner_principal[`border_radius`] || `24px`);
  raiz.style.setProperty(`--tv-button-primary-background`, button_primary[`background_color`] || tema_tokens[`color.accent`] || `#111111`);
  raiz.style.setProperty(`--tv-button-primary-text`, button_primary[`text_color`] || `#ffffff`);
  raiz.style.setProperty(`--tv-button-primary-radius`, button_primary[`border_radius`] || `999px`);
  raiz.style.setProperty(`--tv-card-product-background`, card_product[`background_color`] || tema_tokens[`color.surface`] || `#ffffff`);
  raiz.style.setProperty(`--tv-card-product-border`, card_product[`border_color`] || tema_tokens[`color.border`] || `#dddddd`);
  raiz.style.setProperty(`--tv-card-product-shadow`, card_product[`shadow`] || `0 14px 32px rgba(17, 17, 17, 0.08)`);
  raiz.style.setProperty(`--tv-input-search-background`, input_search[`background_color`] || tema_tokens[`color.surface`] || `#ffffff`);
  raiz.style.setProperty(`--tv-input-search-border`, input_search[`border_color`] || tema_tokens[`color.border`] || `#dddddd`);
  raiz.style.setProperty(`--tv-badge-background`, badge[`background_color`] || tema_tokens[`color.secondary`] || `#f7e5e9`);
  raiz.style.setProperty(`--tv-badge-text`, badge[`text_color`] || tema_tokens[`color.text`] || `#8d5d68`);
  raiz.style.setProperty(`--tv-badge-border`, badge[`border_color`] || tema_tokens[`color.border`] || `#e8c6ce`);
  raiz.style.setProperty(`--tv-footer-background`, footer[`background_color`] || tema_tokens[`color.secondary`] || `#edd7dd`);
  raiz.style.setProperty(`--tv-footer-background-alt`, footer[`background_alt_color`] || footer[`background_color`] || tema_tokens[`color.secondary`] || `#e7cfd6`);
  raiz.style.setProperty(`--tv-footer-text`, footer[`text_color`] || tema_tokens[`color.text`] || `#5e454d`);
  raiz.style.setProperty(`--tv-footer-link`, footer[`link_color`] || footer[`text_color`] || tema_tokens[`color.text`] || `#6f525b`);
  raiz.style.setProperty(`--tv-footer-link-hover-background`, footer[`link_hover_background`] || `rgba(255, 255, 255, 0.5)`);
  raiz.style.setProperty(`--tv-footer-border`, footer[`border_top_color`] || tema_tokens[`color.border`] || `#e5c9d0`);
  raiz.style.setProperty(`--tv-topbar-background`, topbar[`background_color`] || tema_tokens[`color.accent`] || `#dba2b0`);
  raiz.style.setProperty(`--tv-topbar-text`, topbar[`text_color`] || `#fffaf9`);
  raiz.style.setProperty(`--tv-hero-panel-background`, hero_panel[`background_color`] || `#fff5f7`);
  raiz.style.setProperty(`--tv-hero-panel-border`, hero_panel[`border_color`] || tema_tokens[`color.border`] || `#ecd1d8`);
  raiz.style.setProperty(`--tv-section-soft-background`, section_soft[`background_color`] || `#fff8fa`);
  raiz.style.setProperty(`--tv-product-price`, product_price[`text_color`] || tema_tokens[`color.accent`] || `#b9677c`);
  raiz.style.setProperty(`--tv-product-media-background`, product_media[`background_color`] || `#f8edf0`);
  raiz.style.setProperty(`--tv-testimonial-background`, testimonial[`background_color`] || `#fff7f9`);

  if (componentes[`header`]) {
    tpub.tv_header_tienda_publica.style.backgroundColor = componentes[`header`][`background_color`] || ``;
    tpub.tv_header_tienda_publica.style.borderBottom    = componentes[`header`][`border_bottom`] || ``;
  }
}
/**
 * Función encargada de alternar el menú móvil del frente público.
 *
 * @param      boolean  visible  Estado visual del menú.
 */
function alternar_menu_tienda_publica(visible) {
  if (visible === true) {
    tpub.tv_nav_tienda_publica.classList.add(`tv_nav_activo`);
    tpub.div_backdrop_tienda_publica.classList.remove(`tv_oculto`);
    return;
  }

  tpub.tv_nav_tienda_publica.classList.remove(`tv_nav_activo`);
  tpub.div_backdrop_tienda_publica.classList.add(`tv_oculto`);
}
/**
 * Función encargada de mostrar alertas del frente público.
 *
 * @param      string  tipo     Tipo de alerta.
 * @param      string  mensaje  Mensaje a mostrar.
 */
function mostrar_alerta_tienda_publica(tipo, mensaje) {
  limpiar_alerta_tienda_publica();
  tpub.div_mensaje_tienda_publica.innerHTML = template_alerta_tienda_publica(tipo, mensaje);
  tpub.temporizador_alerta = setTimeout(function() {
    limpiar_alerta_tienda_publica();
  }, 5000);
}
/**
 * Función encargada de limpiar la alerta visible.
 */
function limpiar_alerta_tienda_publica() {
  if (tpub.temporizador_alerta) {
    clearTimeout(tpub.temporizador_alerta);
    tpub.temporizador_alerta = null;
  }

  tpub.div_mensaje_tienda_publica.innerHTML = ``;
}
