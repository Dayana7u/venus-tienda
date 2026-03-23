# Mapa de esquemas y tablas

## Esquema system
Se evidencia que, este esquema concentra la parametrización global y reusable del proyecto.

| Tabla | Tipo | Propósito |
|---|---|---|
| temas | Maestra | Registrar temas visuales disponibles. |
| tema_tokens | Parametrizable | Registrar tokens visuales por tema. |
| tema_componentes | Parametrizable | Registrar configuración visual por componente. |
| branding | Parametrizable | Registrar identidad visual general. |
| parametro_grupos | Maestra | Agrupar parámetros por categoría funcional. |
| parametros | Maestra | Registrar catálogo de parámetros. |
| parametro_valores | Parametrizable | Registrar valores asignados a cada parámetro. |
| modulos | Maestra | Registrar módulos habilitables. |
| modulo_configuraciones | Parametrizable | Registrar configuraciones por módulo. |
| integraciones | Maestra | Registrar integraciones disponibles. |
| integracion_configuraciones | Parametrizable | Registrar configuración técnica de cada integración. |
| plantillas | Parametrizable | Registrar plantillas configurables. |
| menus | Parametrizable | Registrar la navegación configurable. |
| bitacora_cambios | Auditoría | Registrar eventos funcionales y técnicos relevantes. |
| logs_aplicacion | Auditoría | Registrar errores y diagnósticos. |

## Esquema public
Se evidencia que, este esquema concentra la seguridad y la operación general inicial del proyecto.

| Tabla | Tipo | Propósito |
|---|---|---|
| usuarios | Operativa | Registrar usuarios de acceso. |
| roles | Maestra | Registrar roles de acceso. |
| permisos | Maestra | Registrar permisos funcionales. |
| roles_permisos | Relacional | Relacionar roles con permisos. |
| usuarios_roles | Relacional | Relacionar usuarios con roles. |

## Observaciones de continuidad
- Las tablas del esquema `system` ya quedan cerradas a nivel estructural.
- La siguiente etapa se enfoca en código y documentación.
- Las pruebas visuales se dejan para después del login.
