# Diccionario de datos base

## Esquema public

### usuarios
- PK: `usuario_id`
- Campos principales: `nombres`, `apellidos`, `login`, `correo`, `clave`, `sw_superusuario`, `ultimo_ingreso`
- Trazabilidad: `estado`, `borrado`, `usuario_creacion`, `fecha_creacion`, `usuario_modificacion`, `fecha_modificacion`, `usuario_borrado`, `fecha_borrado`

### roles
- PK: `rol_id`
- Campos principales: `codigo`, `nombre`, `descripcion`, `sw_predeterminado`
- Trazabilidad obligatoria completa

### permisos
- PK: `permiso_id`
- Campos principales: `codigo`, `nombre`, `descripcion`, `modulo`, `tipo_permiso`, `orden`
- Trazabilidad obligatoria completa

### roles_permisos
- PK: `rol_permiso_id`
- Relaciones: `rol_id`, `permiso_id`
- Trazabilidad obligatoria completa

### usuarios_roles
- PK: `usuario_rol_id`
- Relaciones: `usuario_id`, `rol_id`
- Trazabilidad obligatoria completa

## Esquema system

### temas
- PK: `tema_id`
- Campos principales: `codigo`, `nombre`, `descripcion`, `sw_predeterminado`
- Trazabilidad obligatoria completa

### tema_tokens
- PK: `tema_token_id`
- Relaciones: `tema_id`
- Campos principales: `token`, `valor`, `descripcion`, `orden`
- Trazabilidad obligatoria completa

### tema_componentes
- PK: `tema_componente_id`
- Relaciones: `tema_id`
- Campos principales: `componente`, `propiedad`, `valor`, `descripcion`, `orden`
- Trazabilidad obligatoria completa

### branding
- PK: `branding_id`
- Campos principales: `nombre_comercial`, `razon_social`, `nit`, `correo`, `telefono`, `logo_principal`, `logo_alterno`, `favicon`
- Trazabilidad obligatoria completa

### parametro_grupos
- PK: `parametro_grupo_id`
- Campos principales: `codigo`, `nombre`, `descripcion`, `orden`
- Trazabilidad obligatoria completa

### parametros
- PK: `parametro_id`
- Relaciones: `parametro_grupo_id`
- Campos principales: `codigo`, `nombre`, `descripcion`, `tipo_dato`, `valor_defecto`, `sw_requerido`, `sw_parametrizable`
- Trazabilidad obligatoria completa

### parametro_valores
- PK: `parametro_valor_id`
- Relaciones: `parametro_id`
- Campos principales: `valor`, `descripcion`, `orden`
- Trazabilidad obligatoria completa

### modulos
- PK: `modulo_id`
- Campos principales: `codigo`, `nombre`, `descripcion`, `ruta`, `icono`, `orden`, `sw_habilitado`
- Trazabilidad obligatoria completa

### modulo_configuraciones
- PK: `modulo_configuracion_id`
- Relaciones: `modulo_id`
- Campos principales: `clave`, `valor`, `descripcion`
- Trazabilidad obligatoria completa

### integraciones
- PK: `integracion_id`
- Campos principales: `codigo`, `nombre`, `descripcion`, `tipo`, `sw_habilitada`
- Trazabilidad obligatoria completa

### integracion_configuraciones
- PK: `integracion_configuracion_id`
- Relaciones: `integracion_id`
- Campos principales: `clave`, `valor`, `descripcion`, `sw_encriptado`
- Trazabilidad obligatoria completa

### plantillas
- PK: `plantilla_id`
- Campos principales: `codigo`, `nombre`, `tipo`, `asunto`, `contenido`, `descripcion`
- Trazabilidad obligatoria completa

### menus
- PK: `menu_id`
- Relaciones: `modulo_id`, `menu_padre_id`
- Campos principales: `codigo`, `nombre`, `ruta`, `icono`, `orden`, `sw_visible`
- Trazabilidad obligatoria completa

### bitacora_cambios
- PK: `bitacora_cambio_id`
- Campos principales: `tabla_afectada`, `registro_id`, `accion`, `detalle`, `contexto`
- Trazabilidad obligatoria completa

### logs_aplicacion
- PK: `log_aplicacion_id`
- Campos principales: `nivel`, `modulo`, `archivo`, `funcion`, `linea`, `mensaje`, `detalle`, `contexto`
- Trazabilidad obligatoria completa

## Nota
Se evidencia que, este diccionario resume la base ya creada en SQL y sirve como referencia documental para el desarrollo del backend. No redefine estructuras.
