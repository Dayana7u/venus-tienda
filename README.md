# tienda_virtual

## Estado actual
Proyecto base de tienda virtual trabajado sobre Docker + Ubuntu + Git.

## Avances consolidados
- Configuración local de PostgreSQL para Docker.
- Script base de creación para los esquemas `public` y `system`.
- Capa base de conexión, controlador y modelo para consultas iniciales.
- Vista base de parametrización con JS separado en:
  - `parametrizacion.js`
  - `parametrizacion_peticiones.js`
  - `parametrizacion_template.js`
- Consulta visual para las tablas principales de `system`.
- Patch SQL para adicionar la columna `accion` en las tablas que la requieren dentro de parametrización.

## Pendiente inmediato
- Validar visualmente el módulo cuando exista login.
- Continuar con mantenimientos CRUD por sección.
- Mantener la bitácora y el documento base actualizados con cada avance.
