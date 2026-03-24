/**
 * Función encargada de generar la alerta visual del frente público.
 *
 * @param      string  tipo     Tipo de alerta.
 * @param      string  mensaje  Mensaje visible.
 *
 * @return     string  Estructura HTML.
 */
function template_alerta_tienda_publica(tipo, mensaje) {
  return `
    <div class="tv_alerta tv_alerta_${tipo}">
      <span>${mensaje}</span>
      <button type="button" data-alerta-cerrar="true" aria-label="Cerrar alerta">×</button>
    </div>`;
}
/**
 * Función encargada de generar la barra superior informativa.
 *
 * @param      object  modulo  Datos del módulo público.
 *
 * @return     string  Estructura HTML.
 */
function template_topbar_tienda_publica(modulo) {
  const mensaje = obtener_valor_configuracion_tienda_publica(
    modulo,
    `tienda_publica.topbar_texto`,
    `Despachos de 1 a 8 días hábiles · Maquillaje, skincare y accesorios.`
  );

  return `<div class="tv_topbar_contenido">${mensaje}</div>`;
}
/**
 * Función encargada de generar el hero del frente público.
 *
 * @param      object  branding   Datos de branding.
 * @param      object  modulo     Configuraciones del módulo.
 * @param      object  parametros Parámetros públicos.
 *
 * @return     string  Estructura HTML.
 */
function template_hero_tienda_publica(branding, modulo, parametros) {
  const nombre_comercial = branding.nombre_comercial || parametros[`app.nombre`] || `Tienda Virtual`;
  const tema_activo = obtener_valor_configuracion_tienda_publica(
    modulo,
    `tienda_publica.tema_activo`,
    `PINK_NUDE`
  );
  const hero_etiqueta = obtener_valor_configuracion_tienda_publica(
    modulo,
    `tienda_publica.hero_etiqueta`,
    `Glow diario`
  );
  const hero_titulo = obtener_valor_configuracion_tienda_publica(
    modulo,
    `tienda_publica.hero_titulo`,
    `Belleza cotidiana con imagen limpia y profesional`
  );
  const hero_descripcion = obtener_valor_configuracion_tienda_publica(
    modulo,
    `tienda_publica.hero_descripcion`,
    `Portada base enfocada en maquillaje, skincare, accesorios y venta digital con identidad femenina y elegante.`
  );
  const hero_boton_primario = obtener_valor_configuracion_tienda_publica(
    modulo,
    `tienda_publica.hero_boton_primario`,
    `Comprar novedades`
  );
  const hero_boton_secundario = obtener_valor_configuracion_tienda_publica(
    modulo,
    `tienda_publica.hero_boton_secundario`,
    `Ver skincare`
  );
  const hero_item_1 = obtener_valor_configuracion_tienda_publica(
    modulo,
    `tienda_publica.hero_item_1`,
    `Envíos a todo Colombia`
  );
  const hero_item_2 = obtener_valor_configuracion_tienda_publica(
    modulo,
    `tienda_publica.hero_item_2`,
    `Pago seguro y atención cercana`
  );
  const hero_item_3 = obtener_valor_configuracion_tienda_publica(
    modulo,
    `tienda_publica.hero_item_3`,
    `Tema pink nude parametrizable`
  );
  const hero_panel_titulo = obtener_valor_configuracion_tienda_publica(
    modulo,
    `tienda_publica.hero_panel_titulo`,
    `Base lista para maquillaje, skincare y accesorios`
  );
  const hero_panel_texto = obtener_valor_configuracion_tienda_publica(
    modulo,
    `tienda_publica.hero_panel_texto`,
    `La vista pública toma branding, tema, menús y configuraciones desde base de datos sin tocar la lógica comercial.`
  );

  return `
    <div class="tv_hero_contenido">
      <div class="tv_hero_texto">
        <span class="tv_etiqueta">${hero_etiqueta}</span>
        <h1>${hero_titulo}</h1>
        <p>${hero_descripcion}</p>
        <div class="tv_hero_acciones">
          <a href="#seccion_catalogo_publico" class="tv_btn tv_btn_principal">${hero_boton_primario}</a>
          <a href="#seccion_contacto_publico" class="tv_btn tv_btn_secundario">${hero_boton_secundario}</a>
        </div>
        <ul class="tv_hero_datos">
          <li>${hero_item_1}</li>
          <li>${hero_item_2}</li>
          <li>${hero_item_3}</li>
        </ul>
      </div>
      <div class="tv_hero_visual">
        <article class="tv_hero_panel">
          <span class="tv_etiqueta">Tema activo ${tema_activo}</span>
          <h3>${nombre_comercial}</h3>
          <p>${hero_panel_texto}</p>
          <div class="tv_hero_mosaico">
            <div class="tv_hero_mosaico_tarjeta tv_hero_mosaico_tarjeta_uno">
              <span>Makeup</span>
            </div>
            <div class="tv_hero_mosaico_tarjeta tv_hero_mosaico_tarjeta_dos">
              <span>Skin</span>
            </div>
            <div class="tv_hero_mosaico_tarjeta tv_hero_mosaico_tarjeta_tres">
              <span>Glow</span>
            </div>
          </div>
          <strong class="tv_hero_respaldo">${hero_panel_titulo}</strong>
        </article>
      </div>
    </div>`;
}
/**
 * Función encargada de generar el menú principal público.
 *
 * @param      array  menus  Listado de menús visibles.
 *
 * @return     string  Estructura HTML.
 */
function template_menu_tienda_publica(menus) {
  if (!Array.isArray(menus) || menus.length === 0) {
    return `<a href="#seccion_inicio_publico" class="tv_nav_link">Inicio</a>`;
  }

  return menus.map(function(menu) {
    return `<a href="${obtener_ancla_menu_tienda_publica(menu.codigo)}" class="tv_nav_link">${menu.nombre}</a>`;
  }).join(``);
}
/**
 * Función encargada de generar el bloque de beneficios del frente público.
 *
 * @param      object  modulo  Datos del módulo público.
 *
 * @return     string  Estructura HTML.
 */
function template_beneficios_tienda_publica(modulo) {
  const configuraciones = modulo.configuraciones || {};
  const items = [
    {
      titulo      : `Tema y branding activos`,
      descripcion : `La portada toma colores, tipografías, menús y textos desde la parametrización ya registrada.`
    },
    {
      titulo      : `Base reusable por cards`,
      descripcion : `El frente queda listo para crecer por bloques reutilizables sin depender de modales.`
    },
    {
      titulo      : `Controles visibles`,
      descripcion : `Buscador: ${obtener_texto_estado_configuracion_tienda_publica(configuraciones, `tienda_publica.mostrar_buscador`)} · Carrito: ${obtener_texto_estado_configuracion_tienda_publica(configuraciones, `tienda_publica.mostrar_carrito`)}.`
    }
  ];

  return `
    <div class="tv_bloque_encabezado">
      <span class="tv_etiqueta">Experiencia base</span>
      <h2>Vista comercial separada del panel administrativo</h2>
      <p>La tienda pública mantiene una base limpia y profesional para cosmética, skincare y accesorios.</p>
    </div>
    <div class="tv_grid_beneficios">
      ${items.map(function(item) {
        return `
          <article class="tv_tarjeta_info">
            <h3>${item.titulo}</h3>
            <p>${item.descripcion}</p>
          </article>`;
      }).join(``)}
    </div>`;
}
/**
 * Función encargada de generar el bloque de categorías destacadas.
 *
 * @param      object  modulo  Datos del módulo público.
 *
 * @return     string  Estructura HTML.
 */
function template_categorias_tienda_publica(modulo) {
  const categorias = [1, 2, 3, 4].map(function(indice) {
    return {
      etiqueta    : obtener_valor_configuracion_tienda_publica(modulo, `tienda_publica.categoria_${indice}_etiqueta`, `Colección ${indice}`),
      titulo      : obtener_valor_configuracion_tienda_publica(modulo, `tienda_publica.categoria_${indice}_titulo`, `Categoría ${indice}`),
      descripcion : obtener_valor_configuracion_tienda_publica(modulo, `tienda_publica.categoria_${indice}_descripcion`, `Descripción base de la categoría.`)
    };
  });

  return `
    <div class="tv_bloque_encabezado">
      <span class="tv_etiqueta">Categorías</span>
      <h2>Bloques pensados para belleza y cuidado personal</h2>
      <p>La portada ya toma un enfoque más profesional para venta de maquillaje, skincare, accesorios y referencias de temporada.</p>
    </div>
    <div class="tv_grid_categorias">
      ${categorias.map(function(categoria, indice) {
        return `
          <article class="tv_categoria_card">
            <div class="tv_categoria_media tv_categoria_media_${indice + 1}"></div>
            <span class="tv_etiqueta">${categoria.etiqueta}</span>
            <h3>${categoria.titulo}</h3>
            <p>${categoria.descripcion}</p>
          </article>`;
      }).join(``)}
    </div>`;
}
/**
 * Función encargada de generar el bloque de productos destacados.
 *
 * @param      object  modulo  Datos del módulo público.
 *
 * @return     string  Estructura HTML.
 */
function template_destacados_tienda_publica(modulo) {
  const productos = [1, 2, 3, 4].map(function(indice) {
    return {
      categoria   : obtener_valor_configuracion_tienda_publica(modulo, `tienda_publica.destacado_${indice}_categoria`, `Destacado`),
      nombre      : obtener_valor_configuracion_tienda_publica(modulo, `tienda_publica.destacado_${indice}_nombre`, `Producto ${indice}`),
      descripcion : obtener_valor_configuracion_tienda_publica(modulo, `tienda_publica.destacado_${indice}_descripcion`, `Descripción breve del producto.`),
      precio      : obtener_valor_configuracion_tienda_publica(modulo, `tienda_publica.destacado_${indice}_precio`, `$0`)
    };
  });

  return `
    <div class="tv_bloque_encabezado">
      <span class="tv_etiqueta">Selección</span>
      <h2>Productos destacados con apariencia más comercial</h2>
      <p>Queda lista una grilla profesional para conectar más adelante el catálogo real desde base de datos.</p>
    </div>
    <div class="tv_grid_productos">
      ${productos.map(function(producto, indice) {
        return `
          <article class="tv_producto_card">
            <div class="tv_producto_media tv_producto_media_${indice + 1}">
              <span>${producto.categoria}</span>
            </div>
            <span class="tv_etiqueta">${producto.categoria}</span>
            <h3>${producto.nombre}</h3>
            <p>${producto.descripcion}</p>
            <div class="tv_producto_pie">
              <strong class="tv_producto_precio">${producto.precio}</strong>
              <button type="button" class="tv_btn tv_btn_secundario" disabled>Explorar</button>
            </div>
          </article>`;
      }).join(``)}
    </div>`;
}
/**
 * Función encargada de generar el bloque de rutina sugerida.
 *
 * @param      object  modulo  Datos del módulo público.
 *
 * @return     string  Estructura HTML.
 */
function template_rutina_tienda_publica(modulo) {
  const pasos = [1, 2, 3].map(function(indice) {
    return {
      titulo      : obtener_valor_configuracion_tienda_publica(modulo, `tienda_publica.rutina_${indice}_titulo`, `Paso ${indice}`),
      descripcion : obtener_valor_configuracion_tienda_publica(modulo, `tienda_publica.rutina_${indice}_descripcion`, `Descripción del paso.`)
    };
  });

  return `
    <div class="tv_bloque_encabezado">
      <span class="tv_etiqueta">Rutina</span>
      <h2>Una estructura más cercana al mundo skincare y beauty</h2>
      <p>Este bloque deja visible una narrativa comercial que se parece más a una tienda profesional de cuidado personal.</p>
    </div>
    <div class="tv_grid_rutina">
      ${pasos.map(function(paso, indice) {
        return `
          <article class="tv_rutina_card">
            <div class="tv_rutina_numero">0${indice + 1}</div>
            <h3>${paso.titulo}</h3>
            <p>${paso.descripcion}</p>
          </article>`;
      }).join(``)}
    </div>`;
}
/**
 * Función encargada de generar el bloque de ofertas.
 *
 * @param      object  modulo  Datos del módulo público.
 *
 * @return     string  Estructura HTML.
 */
function template_ofertas_tienda_publica(modulo) {
  const mostrar_ofertas = validar_configuracion_tienda_publica(modulo.configuraciones || {}, `tienda_publica.mostrar_ofertas`);

  if (mostrar_ofertas !== true) {
    return `
      <article class="tv_tarjeta_info">
        <h3>Bloque de ofertas inactivo</h3>
        <p>La configuración actual del módulo público tiene este bloque oculto.</p>
      </article>`;
  }

  const oferta_titulo = obtener_valor_configuracion_tienda_publica(
    modulo,
    `tienda_publica.oferta_titulo`,
    `Promociones por temporada`
  );
  const oferta_descripcion = obtener_valor_configuracion_tienda_publica(
    modulo,
    `tienda_publica.oferta_descripcion`,
    `Este espacio queda listo para campañas visuales como navidad, san valentín o liquidaciones.`
  );
  const oferta_secundaria_titulo = obtener_valor_configuracion_tienda_publica(
    modulo,
    `tienda_publica.oferta_secundaria_titulo`,
    `Cambios visuales sin tocar la lógica`
  );
  const oferta_secundaria_descripcion = obtener_valor_configuracion_tienda_publica(
    modulo,
    `tienda_publica.oferta_secundaria_descripcion`,
    `La tienda puede variar apariencia por tema y mantener la misma base administrativa y comercial.`
  );

  return `
    <div class="tv_bloque_encabezado">
      <span class="tv_etiqueta">Campañas</span>
      <h2>Promociones configurables</h2>
      <p>Las campañas visuales del frente público siguen quedando soportadas por la parametrización del módulo.</p>
    </div>
    <div class="tv_grid_ofertas">
      <article class="tv_tarjeta_oferta">
        <span class="tv_etiqueta">Oferta base</span>
        <h3>${oferta_titulo}</h3>
        <p>${oferta_descripcion}</p>
      </article>
      <article class="tv_tarjeta_oferta">
        <span class="tv_etiqueta">Tema activo</span>
        <h3>${oferta_secundaria_titulo}</h3>
        <p>${oferta_secundaria_descripcion}</p>
      </article>
    </div>`;
}
/**
 * Función encargada de generar el bloque de testimonios.
 *
 * @param      object  modulo  Datos del módulo público.
 *
 * @return     string  Estructura HTML.
 */
function template_testimonios_tienda_publica(modulo) {
  const testimonios = [1, 2].map(function(indice) {
    return {
      nombre : obtener_valor_configuracion_tienda_publica(modulo, `tienda_publica.testimonio_${indice}_nombre`, `Cliente ${indice}`),
      texto  : obtener_valor_configuracion_tienda_publica(modulo, `tienda_publica.testimonio_${indice}_texto`, `Comentario base de experiencia.`)
    };
  });

  return `
    <div class="tv_bloque_encabezado">
      <span class="tv_etiqueta">Confianza</span>
      <h2>Respaldo visual para una tienda más profesional</h2>
      <p>Se mantiene la línea femenina y clara del tema actual, pero con una composición más cercana a una tienda beauty real.</p>
    </div>
    <div class="tv_grid_testimonios">
      ${testimonios.map(function(testimonio) {
        return `
          <article class="tv_testimonio_card">
            <p>“${testimonio.texto}”</p>
            <strong>${testimonio.nombre}</strong>
          </article>`;
      }).join(``)}
    </div>`;
}
/**
 * Función encargada de generar el bloque de contacto.
 *
 * @param      object  branding  Datos de branding.
 * @param      object  modulo    Datos del módulo público.
 *
 * @return     string  Estructura HTML.
 */
function template_contacto_tienda_publica(branding, modulo) {
  const telefono = branding.telefono_contacto || `Pendiente por parametrizar`;
  const correo = branding.correo_contacto || `Pendiente por parametrizar`;
  const direccion = branding.direccion || `Pendiente por parametrizar`;
  const contacto_titulo = obtener_valor_configuracion_tienda_publica(
    modulo,
    `tienda_publica.contacto_titulo`,
    `Canales base de atención`
  );
  const contacto_descripcion = obtener_valor_configuracion_tienda_publica(
    modulo,
    `tienda_publica.contacto_descripcion`,
    `Los datos visibles se leen desde branding activo para conservar el enfoque parametrizable.`
  );

  return `
    <div class="tv_bloque_encabezado">
      <span class="tv_etiqueta">Contacto</span>
      <h2>${contacto_titulo}</h2>
      <p>${contacto_descripcion}</p>
    </div>
    <div class="tv_grid_contacto">
      <article class="tv_tarjeta_info">
        <h3>Correo</h3>
        <p>${correo}</p>
      </article>
      <article class="tv_tarjeta_info">
        <h3>Teléfono</h3>
        <p>${telefono}</p>
      </article>
      <article class="tv_tarjeta_info">
        <h3>Dirección</h3>
        <p>${direccion}</p>
      </article>
    </div>`;
}
/**
 * Función encargada de generar el pie de página público.
 *
 * @param      object  branding  Datos de branding.
 * @param      array   menus     Listado de menús visibles.
 *
 * @return     string  Estructura HTML.
 */
function template_footer_tienda_publica(branding, menus) {
  const texto_footer = branding.texto_footer || `Todos los derechos reservados.`;
  const menu_html = template_menu_tienda_publica(menus);

  return `
    <div class="tv_footer_contenido">
      <div>
        <h3>${branding.nombre_comercial || `Tienda Virtual`}</h3>
        <p>${texto_footer}</p>
      </div>
      <nav class="tv_footer_nav">${menu_html}</nav>
    </div>`;
}
/**
 * Función encargada de generar el buscador visible del header.
 *
 * @param      object  modulo  Datos del módulo público.
 *
 * @return     string  Estructura HTML.
 */
function template_buscador_tienda_publica(modulo) {
  const mostrar_buscador = validar_configuracion_tienda_publica(modulo.configuraciones || {}, `tienda_publica.mostrar_buscador`);

  if (mostrar_buscador !== true) {
    return ``;
  }

  return `
    <label class="tv_buscador_campo" for="buscar_tienda_publica">
      <input type="text" id="buscar_tienda_publica" placeholder="Buscar próximamente" autocomplete="off" disabled>
    </label>`;
}
/**
 * Función encargada de generar el acceso visual al carrito.
 *
 * @param      object  modulo  Datos del módulo público.
 *
 * @return     string  Estructura HTML.
 */
function template_carrito_tienda_publica(modulo) {
  const mostrar_carrito = validar_configuracion_tienda_publica(modulo.configuraciones || {}, `tienda_publica.mostrar_carrito`);

  if (mostrar_carrito !== true) {
    return ``;
  }

  return `<button type="button" class="tv_btn tv_btn_icono" disabled>Carrito</button>`;
}
/**
 * Función encargada de obtener el ancla visual de cada menú público.
 *
 * @param      string  codigo  Código del menú.
 *
 * @return     string  Ruta interna del bloque.
 */
function obtener_ancla_menu_tienda_publica(codigo) {
  const anclas = {
    MENU_INICIO_PUBLICO   : `#seccion_inicio_publico`,
    MENU_CATALOGO_PUBLICO : `#seccion_catalogo_publico`,
    MENU_OFERTAS_PUBLICO  : `#seccion_ofertas_publico`,
    MENU_CONTACTO_PUBLICO : `#seccion_contacto_publico`
  };

  return anclas[codigo] || `#seccion_inicio_publico`;
}
/**
 * Función encargada de obtener el valor visible de una configuración del módulo.
 *
 * @param      object  modulo          Datos del módulo público.
 * @param      string  codigo          Código de la configuración.
 * @param      string  valor_defecto   Valor por defecto.
 *
 * @return     string  Valor visible.
 */
function obtener_valor_configuracion_tienda_publica(modulo, codigo, valor_defecto) {
  const configuraciones = modulo.configuraciones || {};

  if (!configuraciones[codigo]) {
    return valor_defecto;
  }

  const valor = configuraciones[codigo].valor;

  if (valor === null || valor === undefined || valor === ``) {
    return valor_defecto;
  }

  return valor;
}
/**
 * Función encargada de validar una configuración binaria del módulo público.
 *
 * @param      object  configuraciones  Configuraciones del módulo.
 * @param      string  codigo           Código a validar.
 *
 * @return     boolean  Resultado de la validación.
 */
function validar_configuracion_tienda_publica(configuraciones, codigo) {
  if (!configuraciones[codigo]) {
    return false;
  }

  return configuraciones[codigo].valor === `1`;
}
/**
 * Función encargada de obtener el texto visible de una configuración binaria.
 *
 * @param      object  configuraciones  Configuraciones del módulo.
 * @param      string  codigo           Código a validar.
 *
 * @return     string  Texto visible.
 */
function obtener_texto_estado_configuracion_tienda_publica(configuraciones, codigo) {
  return validar_configuracion_tienda_publica(configuraciones, codigo) ? `activo` : `inactivo`;
}
