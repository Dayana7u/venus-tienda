# Bitácora general de cambios

## Línea de tiempo de avances
### 2026-03-23
#### Base técnica inicial
- Se definió Docker + Ubuntu + Git como flujo oficial del proyecto.
- Se agregó configuración local de PostgreSQL para desarrollo.
- Se dejó la estructura SQL inicial para seguridad y parametrización.

#### Capa base `system`
- Se agregó `configdb.php` como punto central de conexión.
- Se creó la capa base de consultas sobre el esquema `system`.
- Se documentó la trazabilidad inicial del proyecto.

#### Parametrización base
- Se agregó la vista inicial de parametrización.
- Se separó la capa JavaScript en archivo principal, peticiones y template.
- Se dejaron consultas base para temas, branding, parámetros, módulos, integraciones y menús.

#### Login base
- Se agregó `backend/index.php` como punto de entrada para navegador.
- Se agregó `backend/app/Views/login.php`.
- Se agregó `backend/app/Controllers/login_controller.php`.
- Se agregó `backend/app/Models/login_model.class.php`.
- Se agregaron `backend/public/assets/js/login.js`, `login_peticiones.js` y `login_template.js`.
- Se agregó `backend/cerrar_sesion.php` para limpiar la sesión activa.
- Se ajustó `parametrizacion.php` para exigir sesión activa antes de cargar el módulo.
- Se dejó el proyecto listo para iniciar validación en navegador con el usuario base del SQL.

### 2026-03-24
#### Seguridad administrativa
- Se habilitó la vista `backend/app/Views/seguridad.php` como módulo real del panel administrativo.
- Se dejó la navegación del panel con dos opciones principales: parametrización y seguridad.
- Se incorporó validación de sesión administrativa contra `public.usuarios_sesiones` en login, parametrización y seguridad.
- Se corrigió el cierre de sesión para dejar de usar la columna inexistente `fecha_cierre` y usar `fecha_expiracion` + `fecha_modificacion`.
- Se agregó control de sesiones activas, accesos recientes y cierre de otras sesiones desde seguridad.
- Se agregó cambio de claves de usuarios desde seguridad, con cierre de sesiones relacionadas.
- Se ajustó el guardado de usuarios para almacenar la clave con hash bcrypt.
- Se dejó compatibilidad temporal para claves legadas sin hash y se agregó SQL de migración `004_sql_seguridad_hash_claves.sql`.
