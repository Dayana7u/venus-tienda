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
  tadm.tienda_admin_categoria_id                = document.getElementById(`tienda_admin_categoria_id`);
  tadm.tienda_admin_categoria_codigo            = document.getElementById(`tienda_admin_categoria_codigo`);
  tadm.tienda_admin_categoria_nombre            = document.getElementById(`tienda_admin_categoria_nombre`);
  tadm.tienda_admin_categoria_linea             = document.getElementById(`tienda_admin_categoria_linea`);
  tadm.tienda_admin_categoria_descripcion       = document.getElementById(`tienda_admin_categoria_descripcion`);
  tadm.tienda_admin_categoria_imagen            = document.getElementById(`tienda_admin_categoria_imagen`);
  tadm.tienda_admin_categoria_texto_alternativo = document.getElementById(`tienda_admin_categoria_texto_alternativo`);
  tadm.btn_cancelar_edicion_tienda_admin_categoria = document.getElementById(`btn_cancelar_edicion_tienda_admin_categoria`);
  tadm.titulo_formulario_tienda_admin_categoria = document.getElementById(`titulo_formulario_tienda_admin_categoria`);
// Campos producto
  tadm.tienda_admin_producto_id                 = document.getElementById(`tienda_admin_producto_id`);
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
  tadm.btn_cancelar_edicion_tienda_admin_producto = document.getElementById(`btn_cancelar_edicion_tienda_admin_producto`);
  tadm.titulo_formulario_tienda_admin_producto  = document.getElementById(`titulo_formulario_tienda_admin_producto`);
// Campos imagen
  tadm.tienda_admin_imagen_id                   = document.getElementById(`tienda_admin_imagen_id`);
  tadm.tienda_admin_imagen_producto_id          = document.getElementById(`tienda_admin_imagen_producto_id`);
  tadm.tienda_admin_imagen_texto_alternativo    = document.getElementById(`tienda_admin_imagen_texto_alternativo`);
  tadm.tienda_admin_imagen_archivo              = document.getElementById(`tienda_admin_imagen_archivo`);
  tadm.btn_cancelar_edicion_tienda_admin_imagen = document.getElementById(`btn_cancelar_edicion_tienda_admin_imagen`);
  tadm.titulo_formulario_tienda_admin_imagen    = document.getElementById(`titulo_formulario_tienda_admin_imagen`);
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

  document.addEventListener(`click`, async function(event) {
    if (event.target.matches(`[data-alerta-cerrar="true"]`)) {
      limpiar_alerta_tienda_admin();
      return;
    }

    const accion = event.target.dataset.accion || ``;
    const id = Number(event.target.dataset.id || 0);

    if (accion === ``) {
      return;
    }

    if (accion === `editar-categoria`) {
      cargar_categoria_en_formulario_tienda_admin(id);
      return;
    }

    if (accion === `editar-producto`) {
      cargar_producto_en_formulario_tienda_admin(id);
      return;
    }

    if (accion === `editar-imagen`) {
      cargar_imagen_en_formulario_tienda_admin(id);
      return;
    }

    if (accion === `inactivar-categoria`) {
      await confirmar_inactivacion_tienda_admin(`¿Inactivar esta categoría?`, async function() {
        let peticion = await tienda_admin_inactivar_categoria_peticiones(tadm.token, id);
        await procesar_respuesta_accion_tienda_admin(peticion);
      });
      return;
    }

    if (accion === `inactivar-producto`) {
      await confirmar_inactivacion_tienda_admin(`¿Inactivar este producto?`, async function() {
        let peticion = await tienda_admin_inactivar_producto_peticiones(tadm.token, id);
        await procesar_respuesta_accion_tienda_admin(peticion);
      });
      return;
    }

    if (accion === `inactivar-imagen`) {
      await confirmar_inactivacion_tienda_admin(`¿Quitar esta imagen del listado?`, async function() {
        let peticion = await tienda_admin_inactivar_imagen_peticiones(tadm.token, id);
        await procesar_respuesta_accion_tienda_admin(peticion);
      });
      return;
    }

    if (accion === `pagar-pedido`) {
      let peticion = await tienda_admin_actualizar_pedido_peticiones(tadm.token, id, ``, `pagado`);
      await procesar_respuesta_accion_tienda_admin(peticion);
      return;
    }

    if (accion === `enviar-pedido`) {
      let peticion = await tienda_admin_actualizar_pedido_peticiones(tadm.token, id, `enviado`, ``);
      await procesar_respuesta_accion_tienda_admin(peticion);
      return;
    }

    if (accion === `detalle-pedido`) {
      const pedido = tadm.pedidos.find(function(item) { return Number(item.pedido_tienda_id || 0) === id; });
      if (pedido) {
        mostrar_alerta_tienda_admin(`success`, `${pedido.codigo}: ${pedido.cliente_nombre_completo || `Cliente`} · ${formatear_moneda_tienda_admin(pedido.total || 0)}`);
      }
      return;
    }
  });

  if (tadm.btn_cancelar_edicion_tienda_admin_categoria) {
    tadm.btn_cancelar_edicion_tienda_admin_categoria.addEventListener(`click`, function() {
      resetear_formulario_categoria_tienda_admin();
    });
  }

  if (tadm.btn_cancelar_edicion_tienda_admin_producto) {
    tadm.btn_cancelar_edicion_tienda_admin_producto.addEventListener(`click`, function() {
      resetear_formulario_producto_tienda_admin();
    });
  }

  if (tadm.btn_cancelar_edicion_tienda_admin_imagen) {
    tadm.btn_cancelar_edicion_tienda_admin_imagen.addEventListener(`click`, function() {
      resetear_formulario_imagen_tienda_admin();
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
  formulario.append(`tienda_admin_categoria_id`, tadm.tienda_admin_categoria_id?.value || ``);
  formulario.append(`tienda_admin_categoria_codigo`, tadm.tienda_admin_categoria_codigo.value.trim());
  formulario.append(`tienda_admin_categoria_nombre`, tadm.tienda_admin_categoria_nombre.value.trim());
  formulario.append(`tienda_admin_categoria_linea`, tadm.tienda_admin_categoria_linea.value);
  formulario.append(`tienda_admin_categoria_descripcion`, tadm.tienda_admin_categoria_descripcion.value.trim());
  formulario.append(`tienda_admin_categoria_texto_alternativo`, tadm.tienda_admin_categoria_texto_alternativo.value.trim());

  if (tadm.tienda_admin_categoria_imagen && tadm.tienda_admin_categoria_imagen.files.length > 0) {
    formulario.append(`tienda_admin_categoria_imagen`, tadm.tienda_admin_categoria_imagen.files[0]);
  }

  let peticion = await tienda_admin_guardar_categoria_peticiones(formulario);
  await procesar_respuesta_guardado_tienda_admin(peticion, `categoria`);
}

async function guardar_producto_tienda_admin() {
  const formulario = new FormData();

  formulario.append(`token`, tadm.token);
  formulario.append(`tienda_admin_producto_id`, tadm.tienda_admin_producto_id?.value || ``);
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
  await procesar_respuesta_guardado_tienda_admin(peticion, `producto`);
}

async function guardar_imagen_tienda_admin() {
  const formulario = new FormData();

  formulario.append(`token`, tadm.token);
  formulario.append(`tienda_admin_imagen_id`, tadm.tienda_admin_imagen_id?.value || ``);
  formulario.append(`tienda_admin_imagen_producto_id`, tadm.tienda_admin_imagen_producto_id.value);
  formulario.append(`tienda_admin_imagen_texto_alternativo`, tadm.tienda_admin_imagen_texto_alternativo.value.trim());

  if (tadm.tienda_admin_imagen_archivo && tadm.tienda_admin_imagen_archivo.files.length > 0) {
    formulario.append(`tienda_admin_imagen_archivo`, tadm.tienda_admin_imagen_archivo.files[0]);
  }

  let peticion = await tienda_admin_guardar_imagen_peticiones(formulario);
  await procesar_respuesta_guardado_tienda_admin(peticion, `imagen`);
}

async function procesar_respuesta_guardado_tienda_admin(peticion, tipo_formulario = ``) {
  if (peticion.estado !== true) {
    mostrar_alerta_tienda_admin(`error`, peticion.mensaje);
    return;
  }

  if (tipo_formulario === `categoria`) {
    resetear_formulario_categoria_tienda_admin();
  }
  else if (tipo_formulario === `producto`) {
    resetear_formulario_producto_tienda_admin();
  }
  else if (tipo_formulario === `imagen`) {
    resetear_formulario_imagen_tienda_admin();
  }

  mostrar_alerta_tienda_admin(`success`, peticion.mensaje);
  await listar_dashboard_tienda_admin();
}

async function procesar_respuesta_accion_tienda_admin(peticion) {
  if (peticion.estado !== true) {
    mostrar_alerta_tienda_admin(`error`, peticion.mensaje);
    return;
  }

  mostrar_alerta_tienda_admin(`success`, peticion.mensaje);
  await listar_dashboard_tienda_admin();
}

function cargar_categoria_en_formulario_tienda_admin(categoria_id) {
  const categoria = tadm.categorias.find(function(item) {
    return Number(item.categoria_id || 0) === Number(categoria_id || 0);
  });

  if (!categoria || !tadm.formulario_categoria) {
    return;
  }

  tadm.tienda_admin_categoria_id.value = categoria.categoria_id || ``;
  tadm.tienda_admin_categoria_codigo.value = categoria.codigo || ``;
  tadm.tienda_admin_categoria_nombre.value = categoria.nombre || ``;
  tadm.tienda_admin_categoria_linea.value = categoria.linea || ``;
  tadm.tienda_admin_categoria_descripcion.value = categoria.descripcion || ``;
  tadm.tienda_admin_categoria_texto_alternativo.value = categoria.texto_alternativo || ``;
  tadm.titulo_formulario_tienda_admin_categoria.textContent = `Editar categoría`;
  tadm.btn_cancelar_edicion_tienda_admin_categoria.classList.remove(`tda_admin_btn_oculto`);
  tadm.formulario_categoria.scrollIntoView({behavior : `smooth`, block : `start`});
}

function cargar_producto_en_formulario_tienda_admin(producto_id) {
  const producto = tadm.productos.find(function(item) {
    return Number(item.producto_id || 0) === Number(producto_id || 0);
  });

  if (!producto || !tadm.formulario_producto) {
    return;
  }

  tadm.tienda_admin_producto_id.value = producto.producto_id || ``;
  tadm.tienda_admin_producto_categoria_id.value = producto.categoria_id || ``;
  tadm.tienda_admin_producto_codigo.value = producto.codigo || ``;
  tadm.tienda_admin_producto_nombre.value = producto.nombre || ``;
  tadm.tienda_admin_producto_etiqueta.value = producto.etiqueta || ``;
  tadm.tienda_admin_producto_resumen.value = producto.resumen || ``;
  tadm.tienda_admin_producto_precio_base.value = producto.precio_base || ``;
  tadm.tienda_admin_producto_precio_oferta.value = producto.precio_oferta || ``;
  tadm.tienda_admin_producto_stock.value = producto.stock || ``;
  tadm.tienda_admin_producto_rating.value = producto.rating_promedio || ``;
  tadm.tienda_admin_producto_descripcion.value = producto.descripcion || ``;
  tadm.tienda_admin_producto_texto_alternativo.value = producto.texto_alternativo || ``;
  tadm.titulo_formulario_tienda_admin_producto.textContent = `Editar producto`;
  tadm.btn_cancelar_edicion_tienda_admin_producto.classList.remove(`tda_admin_btn_oculto`);
  tadm.formulario_producto.scrollIntoView({behavior : `smooth`, block : `start`});
}

function cargar_imagen_en_formulario_tienda_admin(producto_imagen_id) {
  const imagen = tadm.imagenes.find(function(item) {
    return Number(item.producto_imagen_id || 0) === Number(producto_imagen_id || 0);
  });

  if (!imagen || !tadm.formulario_imagen) {
    return;
  }

  tadm.tienda_admin_imagen_id.value = imagen.producto_imagen_id || ``;
  tadm.tienda_admin_imagen_producto_id.value = imagen.producto_id || ``;
  tadm.tienda_admin_imagen_texto_alternativo.value = imagen.texto_alternativo || ``;
  tadm.titulo_formulario_tienda_admin_imagen.textContent = `Editar imagen`;
  tadm.btn_cancelar_edicion_tienda_admin_imagen.classList.remove(`tda_admin_btn_oculto`);
  tadm.formulario_imagen.scrollIntoView({behavior : `smooth`, block : `start`});
}

function resetear_formulario_categoria_tienda_admin() {
  if (!tadm.formulario_categoria) {
    return;
  }

  tadm.formulario_categoria.reset();
  if (tadm.tienda_admin_categoria_id) {
    tadm.tienda_admin_categoria_id.value = ``;
  }
  tadm.titulo_formulario_tienda_admin_categoria.textContent = `Crear categoría`;
  tadm.btn_cancelar_edicion_tienda_admin_categoria.classList.add(`tda_admin_btn_oculto`);
}

function resetear_formulario_producto_tienda_admin() {
  if (!tadm.formulario_producto) {
    return;
  }

  tadm.formulario_producto.reset();
  if (tadm.tienda_admin_producto_id) {
    tadm.tienda_admin_producto_id.value = ``;
  }
  tadm.titulo_formulario_tienda_admin_producto.textContent = `Crear producto`;
  tadm.btn_cancelar_edicion_tienda_admin_producto.classList.add(`tda_admin_btn_oculto`);
}

function resetear_formulario_imagen_tienda_admin() {
  if (!tadm.formulario_imagen) {
    return;
  }

  tadm.formulario_imagen.reset();
  if (tadm.tienda_admin_imagen_id) {
    tadm.tienda_admin_imagen_id.value = ``;
  }
  tadm.titulo_formulario_tienda_admin_imagen.textContent = `Registrar imagen`;
  tadm.btn_cancelar_edicion_tienda_admin_imagen.classList.add(`tda_admin_btn_oculto`);
}

async function confirmar_inactivacion_tienda_admin(mensaje, callback) {
  const confirmado = window.confirm(mensaje);

  if (confirmado !== true) {
    return;
  }

  await callback();
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
