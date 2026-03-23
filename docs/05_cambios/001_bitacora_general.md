# Bitácora general del proyecto

## 2026-03-22
### Hito 1. Base técnica y parametrización SQL
Se evidencia que, se creó el archivo `database/sql/001_sql_parametrizacion_base_v2.sql` y se ejecutó sobre PostgreSQL local en Docker para dejar operativos los esquemas `public` y `system`, sus tablas, restricciones, comentarios, índices y registros iniciales.

### Hito 2. Entorno oficial del proyecto
Se evidencia que, el proyecto quedó alineado para trabajo sobre Docker + Ubuntu + Git como flujo oficial de desarrollo y versionamiento.

## 2026-03-23
### Hito 3. Capa base backend sobre system
Se evidencia que, se incorporaron `backend/config/configdb.php`, `backend/app/Models/system_model.class.php` y `backend/app/Controllers/system_controller.php` para centralizar configuración, conexión, logs y consultas base del esquema `system`.

### Hito 4. Corrección de coherencia de conexión
Se evidencia que, se ajustó `backend/config/database.php` para consumir la misma configuración centralizada de `configdb.php`, evitando divergencias entre constantes y conexión PDO.

### Hito 5. Corrección de modelos de parametrización y seguridad
Se evidencia que, `parametrizacion_model.class.php` y `seguridad_model.class.php` dejaron de consumir `configdb.php` como arreglo y quedaron alineados con `conexion_model.class.php`, manteniendo una única fuente técnica de conexión y trazabilidad de errores.

### Hito 6. Corrección de controladores y JavaScript
Se evidencia que, los controladores de `system`, `parametrizacion` y `seguridad` quedaron con validación de token previa, `switch` por acción, respuesta JSON y archivos JavaScript separados para vista y peticiones.

### Hito 7. Ajuste de trazabilidad documental
Se evidencia que, se actualizaron `README.md`, `docs/02_bd/001_mapa_esquemas_y_tablas.md`, `docs/02_bd/002_diccionario_de_datos.md`, `docs/02_bd/003_estandares_sql.md` y este archivo de bitácora para mantener línea de tiempo y continuidad del proyecto.

## Pendiente acordado
- La validación en navegador queda para después del login.
- El siguiente frente continúa sobre parametrización.
