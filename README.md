# Tienda Virtual

## Estado actual
Se evidencia que, el proyecto se encuentra en construcción base sobre Docker + Ubuntu + Git, con PostgreSQL local levantado en contenedor y con el esquema `system` ya cerrado a nivel estructural.

## Entorno de trabajo definido
- Docker Desktop
- WSL2
- Ubuntu
- Git
- PostgreSQL

## Base de datos
- Puerto local PostgreSQL: `5432`
- Base de datos: `tienda_virtual`
- Contenedor de referencia: `tienda_virtual_postgres`

## Backend incorporado
### Configuración
- `backend/config/configdb.php`
- `backend/config/database.php`

### Modelos
- `backend/app/Models/conexion_model.class.php`
- `backend/app/Models/system_model.class.php`
- `backend/app/Models/parametrizacion_model.class.php`
- `backend/app/Models/seguridad_model.class.php`

### Controladores
- `backend/app/Controllers/system_controller.php`
- `backend/app/Controllers/parametrizacion_controller.php`
- `backend/app/Controllers/seguridad_controller.php`

### Vistas
- `backend/app/Views/parametrizacion.php`
- `backend/app/Views/seguridad.php`

### JavaScript
- `backend/public/assets/js/parametrizacion.js`
- `backend/public/assets/js/parametrizacion_peticiones.js`
- `backend/public/assets/js/seguridad.js`
- `backend/public/assets/js/seguridad_peticiones.js`

## Alcance actual
Se evidencia que, la validación en navegador queda aplazada hasta construir login e inicio de sesión. Mientras tanto, el avance se concentra en cerrar la capa base de `system`, mantener coherencia de conexión a PostgreSQL, dejar listados base para `parametrizacion` y `seguridad`, y actualizar la documentación del proyecto con cada cambio importante.

## Próximo frente
- Cerrar completamente `system` a nivel de código.
- Continuar con parametrización sobre las tablas del esquema `system`.
- Aplazar pruebas visuales hasta disponer de acceso autenticado.
