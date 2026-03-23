const token = document.getElementById(`token`).value;

let par = {}; // Objeto general
// Datos generales
  par.listado_temas                  = [];
  par.listado_tema_tokens            = [];
  par.listado_tema_componentes       = [];
  par.listado_branding               = [];
  par.listado_parametro_grupos       = [];
  par.listado_parametros             = [];
  par.listado_parametro_valores      = [];
  par.listado_modulos                = [];
  par.listado_modulo_configuraciones = [];
  par.listado_integraciones          = [];
  par.listado_integracion_configuraciones = [];
  par.listado_plantillas             = [];
  par.listado_menus                  = [];
  par.mensaje_sin_registros          = `Sin información disponible.`;
// Campos
  par.buscar_parametrizacion = document.getElementById(`buscar_parametrizacion`);
// Divs
  par.div_resumen_parametrizacion               = document.getElementById(`div_resumen_parametrizacion`);
  par.div_contenido_temas_parametrizacion       = document.getElementById(`div_contenido_temas_parametrizacion`);
  par.div_contenido_branding_parametrizacion    = document.getElementById(`div_contenido_branding_parametrizacion`);
  par.div_contenido_parametros_parametrizacion  = document.getElementById(`div_contenido_parametros_parametrizacion`);
  par.div_contenido_modulos_parametrizacion     = document.getElementById(`div_contenido_modulos_parametrizacion`);
  par.div_contenido_integraciones_parametrizacion = document.getElementById(`div_contenido_integraciones_parametrizacion`);
  par.div_contenido_menus_parametrizacion       = document.getElementById(`div_contenido_menus_parametrizacion`);
// Botones
  par.btn_recargar_parametrizacion = document.getElementById(`btn_recargar_parametrizacion`);

(async function() {
  await inicializar_parametrizacion();
}());
/**
 * Función encargada de inicializar el módulo de parametrización
 */
const inicializar_parametrizacion = async function() {
  await listar_parametrizacion();
  await gestionar_eventos_parametrizacion();
};
/**
 * Función encargada de registrar los eventos principales del módulo
 */
const gestionar_eventos_parametrizacion = async function() {
  par.btn_recargar_parametrizacion.addEventListener(`click`, async function() {
    await listar_parametrizacion();
  });

  par.buscar_parametrizacion.addEventListener(`input`, async function() {
    await renderizar_parametrizacion();
  });
};
/**
 * Función encargada de consultar la información inicial del módulo
 */
const listar_parametrizacion = async function() {
  let peticion = await parametrizacion_inicializar_peticiones(token);

  if (peticion.estado !== true) {
    await limpiar_contenido_parametrizacion();
    return;
  }

  await asignar_datos_parametrizacion(peticion.datos);
  await renderizar_parametrizacion();
};
/**
 * Función encargada de asignar la información consultada al objeto general
 *
 * @param      object  informacion  Información de parametrización consultada
 */
const asignar_datos_parametrizacion = async function(informacion) {
  par.listado_temas                      = informacion.temas ?? [];
  par.listado_tema_tokens                = informacion.tema_tokens ?? [];
  par.listado_tema_componentes           = informacion.tema_componentes ?? [];
  par.listado_branding                   = informacion.branding ?? [];
  par.listado_parametro_grupos           = informacion.parametro_grupos ?? [];
  par.listado_parametros                 = informacion.parametros ?? [];
  par.listado_parametro_valores          = informacion.parametro_valores ?? [];
  par.listado_modulos                    = informacion.modulos ?? [];
  par.listado_modulo_configuraciones     = informacion.modulo_configuraciones ?? [];
  par.listado_integraciones              = informacion.integraciones ?? [];
  par.listado_integracion_configuraciones = informacion.integracion_configuraciones ?? [];
  par.listado_plantillas                 = informacion.plantillas ?? [];
  par.listado_menus                      = informacion.menus ?? [];
};
/**
 * Función encargada de renderizar la vista de parametrización
 */
const renderizar_parametrizacion = async function() {
  const texto_busqueda = par.buscar_parametrizacion.value.trim().toLowerCase();
  const resumen        = await obtener_resumen_parametrizacion();
  const listado_temas  = await filtrar_parametrizacion_listado(par.listado_temas, texto_busqueda);
  const listado_branding = await filtrar_parametrizacion_listado(par.listado_branding, texto_busqueda);
  const listado_parametros = await filtrar_parametrizacion_listado(par.listado_parametros, texto_busqueda);
  const listado_modulos = await filtrar_parametrizacion_listado(par.listado_modulos, texto_busqueda);
  const listado_integraciones = await filtrar_parametrizacion_listado(par.listado_integraciones, texto_busqueda);
  const listado_menus = await filtrar_parametrizacion_listado(par.listado_menus, texto_busqueda);

  par.div_resumen_parametrizacion.innerHTML = await template_resumen_parametrizacion(resumen);
  par.div_contenido_temas_parametrizacion.innerHTML = await template_bloque_parametrizacion(
    `Temas`,
    `Incluye temas, tokens y componentes visuales.`,
    listado_temas,
    par.mensaje_sin_registros
  );
  par.div_contenido_branding_parametrizacion.innerHTML = await template_bloque_parametrizacion(
    `Branding`,
    `Incluye identidad visual general de la tienda.`,
    listado_branding,
    par.mensaje_sin_registros
  );
  par.div_contenido_parametros_parametrizacion.innerHTML = await template_bloque_parametrizacion(
    `Parámetros`,
    `Incluye grupos, parámetros y valores parametrizables.`,
    listado_parametros,
    par.mensaje_sin_registros
  );
  par.div_contenido_modulos_parametrizacion.innerHTML = await template_bloque_parametrizacion(
    `Módulos`,
    `Incluye módulos y configuraciones habilitables.`,
    listado_modulos,
    par.mensaje_sin_registros
  );
  par.div_contenido_integraciones_parametrizacion.innerHTML = await template_bloque_parametrizacion(
    `Integraciones`,
    `Incluye integraciones, configuraciones técnicas y plantillas.`,
    listado_integraciones,
    par.mensaje_sin_registros
  );
  par.div_contenido_menus_parametrizacion.innerHTML = await template_bloque_parametrizacion(
    `Menús`,
    `Incluye navegación base visible del aplicativo.`,
    listado_menus,
    par.mensaje_sin_registros
  );
};
/**
 * Función encargada de limpiar el contenido de la vista cuando la petición falla
 */
const limpiar_contenido_parametrizacion = async function() {
  par.div_resumen_parametrizacion.innerHTML                 = ``;
  par.div_contenido_temas_parametrizacion.innerHTML         = ``;
  par.div_contenido_branding_parametrizacion.innerHTML      = ``;
  par.div_contenido_parametros_parametrizacion.innerHTML    = ``;
  par.div_contenido_modulos_parametrizacion.innerHTML       = ``;
  par.div_contenido_integraciones_parametrizacion.innerHTML = ``;
  par.div_contenido_menus_parametrizacion.innerHTML         = ``;
};
/**
 * Función encargada de obtener el resumen general de la parametrización
 *
 * @return     array  Resumen de los listados del módulo
 */
const obtener_resumen_parametrizacion = async function() {
  return [
    {
      titulo   : `Temas`,
      cantidad : par.listado_temas.length + par.listado_tema_tokens.length + par.listado_tema_componentes.length,
    },
    {
      titulo   : `Branding`,
      cantidad : par.listado_branding.length,
    },
    {
      titulo   : `Parámetros`,
      cantidad : par.listado_parametro_grupos.length + par.listado_parametros.length + par.listado_parametro_valores.length,
    },
    {
      titulo   : `Módulos`,
      cantidad : par.listado_modulos.length + par.listado_modulo_configuraciones.length,
    },
    {
      titulo   : `Integraciones`,
      cantidad : par.listado_integraciones.length + par.listado_integracion_configuraciones.length + par.listado_plantillas.length,
    },
    {
      titulo   : `Menús`,
      cantidad : par.listado_menus.length,
    },
  ];
};
/**
 * Función encargada de filtrar un listado según el texto de búsqueda
 *
 * @param      array   listado         Listado base del módulo
 * @param      string  texto_busqueda  Texto a buscar en el listado
 *
 * @return     array  Listado filtrado
 */
const filtrar_parametrizacion_listado = async function(listado, texto_busqueda) {
  if (texto_busqueda === ``)
    return listado;

  return listado.filter(function(registro) {
    const valor = Object.values(registro).join(` `).toLowerCase();

    return valor.includes(texto_busqueda);
  });
};
