# Bitácora general

## Línea de tiempo de avances
### 2026-03-22
- Se crea la base SQL inicial para los esquemas `public` y `system`.
- Se configura PostgreSQL local dentro de Docker.

### 2026-03-23
- Se consolida la capa base de conexión para PostgreSQL.
- Se organiza el proyecto bajo el flujo Docker + Ubuntu + Git.
- Se deja definido que la validación visual se realizará cuando exista login.

### 2026-03-23 - Ajuste parametrización
- Se corrige la estructura del módulo `parametrizacion` para separar:
  - `parametrizacion.js`
  - `parametrizacion_peticiones.js`
  - `parametrizacion_template.js`
- Se ajusta la tabulación del JavaScript para manejar objeto general y funciones globales.
- Se amplía la consulta del módulo para cubrir:
  - `system.temas`
  - `system.tema_tokens`
  - `system.tema_componentes`
  - `system.branding`
  - `system.parametro_grupos`
  - `system.parametros`
  - `system.parametro_valores`
  - `system.modulos`
  - `system.modulo_configuraciones`
  - `system.integraciones`
  - `system.integracion_configuraciones`
  - `system.plantillas`
  - `system.menus`
- Se agrega el patch `002_sql_ajuste_columna_accion_parametrizacion.sql` para adicionar la columna `accion` en las tablas de parametrización que la requieren.
