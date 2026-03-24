let tadm = {};
// Datos generales
  tadm.token                            = document.getElementById(`token`).value;
  tadm.temporizador_alerta              = null;
  tadm.categorias                       = [];
  tadm.productos                        = [];
  tadm.clientes                         = [];
  tadm.pedidos                          = [];
  tadm.imagenes                         = [];
  tadm.ventas                           = {};
  tadm.resumen                          = {};
  tadm.pagina_activa                    = document.body.dataset.paginaActiva || ``;
  tadm.tda_admin_busqueda_general       = document.getElementById(`tda_admin_busqueda_general`);
// Formularios
  tadm.formulario_categoria             = document.getElementById(`formulario_tienda_admin_categoria`);
  tadm.formulario_producto              = document.getElementById(`formulario_tienda_admin_producto`);
  tadm.formulario_imagen                = document.getElementById(`formulario_tienda_admin_imagen`);
// Campos categoria
  tadm.tienda_admin_categoria_codigo            = document.getElementById(`tienda_admin_categoria_codigo`);
  tadm.tienda_admin_categoria_nombre            = document.getElementById(`tienda_admin_categoria_nombre`);
  tadm.tienda_admin_categoria_linea             = document.getElementById(`tienda_admin_categoria_linea`);
  tadm.tienda_admin_categoria_descripcion       = document.getElementById(`tienda_admin_categoria_descripcion`);
  tadm.tienda_admin_categoria_imagen            = document.getElementById(`tienda_admin_categoria_imagen`);
  tadm.tienda_admin_categoria_texto_alternativo = document.getElementById(`tienda_admin_categoria_texto_alternativo`);
// Campos producto
  tadm.tienda_admin_producto_categoria_id       = document.getElementById(`tienda_admin_producto_categoria_id`);
  tadm.tienda_admin_producto_codigo             = document.getElementById(`tienda_admin_producto_codigo`);
  tadm.tienda_admin_producto_nombre             = document.getElementById(`tienda_admin_producto_nombre`);
  tadm.tienda_admin_producto_etiqueta           = document.getElementById(`tienda_admin_producto_etiqueta`);
  tadm.tienda_admin_producto_resumen            = document.getElementById(`tienda_admin_producto_resumen`);
  tadm.tienda_admin_producto_precio_base        = document.getElementById(`tienda_admin_producto_precio_base`);
  tadm.tienda_admin_producto_precio_oferta      = document.getElementById(`tienda_admin_producto_precio_oferta`);
  tadm.tienda_admin_producto_stock              = document.getElementById(`tienda_admin_producto_stock`);
  tadm.tienda_admin_producto_rating             = document.getElementById(`tienda_admin_producto_rating`);
  tadm.tienda_admin_producto_descripcion        = document.getElementById(`tienda_admin_producto_descripcion`);
  tadm.tienda_admin_producto_imagen_principal   = document.getElementById(`tienda_admin_producto_imagen_principal`);
  tadm.tienda_admin_producto_texto_alternativo  = document.getElementById(`tienda_admin_producto_texto_alternativo`);
// Campos imagen
  tadm.tienda_admin_imagen_producto_id          = document.getElementById(`tienda_admin_imagen_producto_id`);
  tadm.tienda_admin_imagen_url                  = document.getElementById(`tienda_admin_imagen_url`);
  tadm.tienda_admin_imagen_texto_alternativo    = document.getElementById(`tienda_admin_imagen_texto_alternativo`);
  tadm.tienda_admin_imagen_archivo              = document.getElementById(`tienda_admin_imagen_archivo`);
// Divs
  tadm.div_mensaje_tienda_admin                 = document.getElementById(`div_mensaje_tienda_admin`);
  tadm.div_metricas_tienda_admin                = document.getElementById(`div_metricas_tienda_admin`);
  tadm.div_paneles_tienda_admin                 = document.getElementById(`div_paneles_tienda_admin`);
  tadm.div_resumen_tienda_admin                 = document.getElementById(`div_resumen_tienda_admin`);
  tadm.div_resumen_ventas_tienda_admin          = document.getElementById(`div_resumen_ventas_tienda_admin`);
  tadm.div_listado_tienda_admin_categorias      = document.getElementById(`div_listado_tienda_admin_categorias`);
  tadm.div_listado_tienda_admin_productos       = document.getElementById(`div_listado_tienda_admin_productos`);
  tadm.div_listado_tienda_admin_imagenes        = document.getElementById(`div_listado_tienda_admin_imagenes`);
  tadm.div_listado_tienda_admin_clientes        = document.getElementById(`div_listado_tienda_admin_clientes`);
  tadm.div_listado_tienda_admin_pedidos         = document.getElementById(`div_listado_tienda_admin_pedidos`);
  tadm.div_listado_tienda_admin_productos_top   = document.getElementById(`div_listado_tienda_admin_productos_top`);

document.addEventListener(`DOMContentLoaded`, async function() {
  await inicializar_tienda_admin();
});

async function inicializar_tienda_admin() {
  await tienda_admin_inicializar_peticiones(tadm.token);
  eventos_tienda_admin();
  await listar_dashboard_tienda_admin();
}

function eventos_tienda_admin() {
  if (tadm.formulario_categoria) {
    tadm.formulario_categoria.addEventListener(`submit`, async function(event) {
      event.preventDefault();
      await guardar_categoria_tienda_admin();
    });
  }

  if (tadm.formulario_producto) {
    tadm.formulario_producto.addEventListener(`submit`, async function(event) {
      event.preventDefault();
      await guardar_producto_tienda_admin();
    });
  }

  if (tadm.formulario_imagen) {
    tadm.formulario_imagen.addEventListener(`submit`, async function(event) {
      event.preventDefault();
      await guardar_imagen_tienda_admin();
    });
  }

  if (tadm.div_mensaje_tienda_admin) {
    tadm.div_mensaje_tienda_admin.addEventListener(`click`, function(event) {
      if (event.target.matches(`[data-alerta-cerrar="true"]`)) {
        limpiar_alerta_tienda_admin();
      }
    });
  }

  if (tadm.tda_admin_busqueda_general) {
    tadm.tda_admin_busqueda_general.addEventListener(`input`, function() {
      renderizar_modulo_tienda_admin();
    });
  }
}

async function listar_dashboard_tienda_admin() {
  let peticion = await tienda_admin_listar_dashboard_peticiones(tadm.token);

  if (peticion.estado !== true) {
    mostrar_alerta_tienda_admin(`error`, peticion.mensaje);
    return;
  }

  tadm.resumen    = peticion.datos.resumen || {};
  tadm.categorias = peticion.datos.categorias || [];
  tadm.productos  = peticion.datos.productos || [];
  tadm.imagenes   = peticion.datos.imagenes || [];
  tadm.clientes   = peticion.datos.clientes || [];
  tadm.pedidos    = peticion.datos.pedidos || [];
  tadm.ventas     = peticion.datos.ventas || {};

  renderizar_modulo_tienda_admin();

  if (tadm.tienda_admin_producto_categoria_id) {
    tadm.tienda_admin_producto_categoria_id.innerHTML = template_tienda_admin_select_categorias(tadm.categorias);
  }

  if (tadm.tienda_admin_imagen_producto_id) {
    tadm.tienda_admin_imagen_producto_id.innerHTML = template_tienda_admin_select_productos(tadm.productos);
  }
}

function renderizar_modulo_tienda_admin() {
  const busqueda = (tadm.tda_admin_busqueda_general?.value || ``).trim().toLowerCase();
  const categorias = filtrar_coleccion_tienda_admin(tadm.categorias, busqueda, [`codigo`, `nombre`, `linea`, `descripcion`, `slug`]);
  const productos  = filtrar_coleccion_tienda_admin(tadm.productos, busqueda, [`codigo`, `nombre`, `categoria_nombre`, `etiqueta`, `resumen`, `descripcion`]);
  const imagenes   = filtrar_coleccion_tienda_admin(tadm.imagenes, busqueda, [`producto_nombre`, `texto_alternativo`, `imagen_url`]);
  const clientes   = filtrar_coleccion_tienda_admin(tadm.clientes, busqueda, [`nombre_completo`, `correo`, `celular`, `ciudad`]);
  const pedidos    = filtrar_coleccion_tienda_admin(tadm.pedidos, busqueda, [`codigo`, `cliente_nombre_completo`, `estado_pedido`, `estado_pago`, `metodo_pago`]);
  const productos_top = filtrar_coleccion_tienda_admin(tadm.ventas.productos_top || [], busqueda, [`producto_nombre`, `producto_codigo`, `producto_slug`]);

  if (tadm.div_metricas_tienda_admin) {
    tadm.div_metricas_tienda_admin.innerHTML = template_tienda_admin_metricas_dashboard(tadm.resumen, tadm.ventas);
  }

  if (tadm.div_paneles_tienda_admin) {
    tadm.div_paneles_tienda_admin.innerHTML = template_tienda_admin_paneles_dashboard(tadm.resumen, tadm.ventas);
  }

  if (tadm.div_resumen_tienda_admin) {
    tadm.div_resumen_tienda_admin.innerHTML = template_tienda_admin_resumen(tadm.resumen);
  }

  if (tadm.div_resumen_ventas_tienda_admin) {
    tadm.div_resumen_ventas_tienda_admin.innerHTML = template_tienda_admin_resumen_ventas(tadm.ventas);
  }

  if (tadm.div_listado_tienda_admin_categorias) {
    tadm.div_listado_tienda_admin_categorias.innerHTML = template_tienda_admin_categorias(categorias);
  }

  if (tadm.div_listado_tienda_admin_productos) {
    tadm.div_listado_tienda_admin_productos.innerHTML = template_tienda_admin_productos(productos);
  }

  if (tadm.div_listado_tienda_admin_imagenes) {
    tadm.div_listado_tienda_admin_imagenes.innerHTML = template_tienda_admin_imagenes(imagenes);
  }

  if (tadm.div_listado_tienda_admin_clientes) {
    tadm.div_listado_tienda_admin_clientes.innerHTML = template_tienda_admin_clientes(clientes);
  }

  if (tadm.div_listado_tienda_admin_pedidos) {
    tadm.div_listado_tienda_admin_pedidos.innerHTML = template_tienda_admin_pedidos(pedidos);
  }

  if (tadm.div_listado_tienda_admin_productos_top) {
    tadm.div_listado_tienda_admin_productos_top.innerHTML = template_tienda_admin_productos_top(productos_top);
  }
}

function filtrar_coleccion_tienda_admin(coleccion, busqueda, campos) {
  if (!busqueda) {
    return coleccion;
  }

  return coleccion.filter(function(item) {
    return campos.some(function(campo) {
      return String(item[campo] || ``).toLowerCase().includes(busqueda);
    });
  });
}

async function guardar_categoria_tienda_admin() {
  const formulario = new FormData();

  formulario.append(`token`, tadm.token);
  formulario.append(`tienda_admin_categoria_codigo`, tadm.tienda_admin_categoria_codigo.value.trim());
  formulario.append(`tienda_admin_categoria_nombre`, tadm.tienda_admin_categoria_nombre.value.trim());
  formulario.append(`tienda_admin_categoria_linea`, tadm.tienda_admin_categoria_linea.value);
  formulario.append(`tienda_admin_categoria_descripcion`, tadm.tienda_admin_categoria_descripcion.value.trim());
  formulario.append(`tienda_admin_categoria_texto_alternativo`, tadm.tienda_admin_categoria_texto_alternativo.value.trim());

  if (tadm.tienda_admin_categoria_imagen && tadm.tienda_admin_categoria_imagen.files.length > 0) {
    formulario.append(`tienda_admin_categoria_imagen`, tadm.tienda_admin_categoria_imagen.files[0]);
  }

  let peticion = await tienda_admin_guardar_categoria_peticiones(formulario);
  await procesar_respuesta_guardado_tienda_admin(peticion, tadm.formulario_categoria);
}

async function guardar_producto_tienda_admin() {
  const formulario = new FormData();

  formulario.append(`token`, tadm.token);
  formulario.append(`tienda_admin_producto_categoria_id`, tadm.tienda_admin_producto_categoria_id.value);
  formulario.append(`tienda_admin_producto_codigo`, tadm.tienda_admin_producto_codigo.value.trim());
  formulario.append(`tienda_admin_producto_nombre`, tadm.tienda_admin_producto_nombre.value.trim());
  formulario.append(`tienda_admin_producto_etiqueta`, tadm.tienda_admin_producto_etiqueta.value.trim());
  formulario.append(`tienda_admin_producto_resumen`, tadm.tienda_admin_producto_resumen.value.trim());
  formulario.append(`tienda_admin_producto_precio_base`, tadm.tienda_admin_producto_precio_base.value);
  formulario.append(`tienda_admin_producto_precio_oferta`, tadm.tienda_admin_producto_precio_oferta.value);
  formulario.append(`tienda_admin_producto_stock`, tadm.tienda_admin_producto_stock.value);
  formulario.append(`tienda_admin_producto_rating`, tadm.tienda_admin_producto_rating.value);
  formulario.append(`tienda_admin_producto_descripcion`, tadm.tienda_admin_producto_descripcion.value.trim());
  formulario.append(`tienda_admin_producto_texto_alternativo`, tadm.tienda_admin_producto_texto_alternativo.value.trim());

  if (tadm.tienda_admin_producto_imagen_principal && tadm.tienda_admin_producto_imagen_principal.files.length > 0) {
    formulario.append(`tienda_admin_producto_imagen_principal`, tadm.tienda_admin_producto_imagen_principal.files[0]);
  }

  let peticion = await tienda_admin_guardar_producto_peticiones(formulario);
  await procesar_respuesta_guardado_tienda_admin(peticion, tadm.formulario_producto);
}

async function guardar_imagen_tienda_admin() {
  const formulario = new FormData();

  formulario.append(`token`, tadm.token);
  formulario.append(`tienda_admin_imagen_producto_id`, tadm.tienda_admin_imagen_producto_id.value);
  formulario.append(`tienda_admin_imagen_url`, tadm.tienda_admin_imagen_url.value.trim());
  formulario.append(`tienda_admin_imagen_texto_alternativo`, tadm.tienda_admin_imagen_texto_alternativo.value.trim());

  if (tadm.tienda_admin_imagen_archivo && tadm.tienda_admin_imagen_archivo.files.length > 0) {
    formulario.append(`tienda_admin_imagen_archivo`, tadm.tienda_admin_imagen_archivo.files[0]);
  }

  let peticion = await tienda_admin_guardar_imagen_peticiones(formulario);
  await procesar_respuesta_guardado_tienda_admin(peticion, tadm.formulario_imagen);
}

async function procesar_respuesta_guardado_tienda_admin(peticion, formulario) {
  if (peticion.estado !== true) {
    mostrar_alerta_tienda_admin(`error`, peticion.mensaje);
    return;
  }

  if (formulario) {
    formulario.reset();
  }

  mostrar_alerta_tienda_admin(`success`, peticion.mensaje);
  await listar_dashboard_tienda_admin();
}

function mostrar_alerta_tienda_admin(tipo, mensaje) {
  limpiar_alerta_tienda_admin();

  if (tadm.div_mensaje_tienda_admin) {
    tadm.div_mensaje_tienda_admin.innerHTML = template_tienda_admin_alerta(tipo, mensaje);
  }

  tadm.temporizador_alerta = setTimeout(function() {
    limpiar_alerta_tienda_admin();
  }, 5000);
}

function limpiar_alerta_tienda_admin() {
  if (tadm.temporizador_alerta) {
    clearTimeout(tadm.temporizador_alerta);
    tadm.temporizador_alerta = null;
  }

  if (tadm.div_mensaje_tienda_admin) {
    tadm.div_mensaje_tienda_admin.innerHTML = ``;
  }
}
