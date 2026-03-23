# Diccionario de datos

## Ajuste columna accion
Se adiciona la columna `accion` mediante patch para las tablas:

- `system.parametros`
- `system.modulo_configuraciones`
- `system.integracion_configuraciones`
- `system.plantillas`
- `system.menus`

La finalidad de esta columna es conservar una referencia funcional reutilizable para lógica configurable, templates visuales y acciones asociadas a parametrización.
