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
- Se ajustó la conexión de `parametrizacion_model.class.php` y `seguridad_model.class.php` para consumir `configdb.php`.
- Se dejó el proyecto listo para iniciar validación en navegador con el usuario base del SQL.


## Avance v12

Se agregó CRUD base en parametrización para temas, branding, parámetros, módulos, integraciones, menús, roles y usuarios.

Se dejó el login usando `public.usuarios_sesiones` para registrar la sesión activa y el cierre de sesión actualiza el histórico del usuario.

Se incorporó diseño minimalista inicial en login y parametrización con paleta azul claro, blanco y negro.

La validación visual ya puede continuar sobre el backend PHP local con el flujo actual de PostgreSQL en Docker.


## Avance v13

Se separó el login administrativo de parametrización del acceso futuro de la tienda mediante variables de sesión prefijadas para la capa administrativa.

Se ajustó el diseño de login y parametrización con enfoque responsive para escritorio, tableta y móvil, manteniendo paleta azul claro, blanco y negro.

Se mejoraron las alertas del módulo con mensajes informativos, éxito, error y confirmación antes del cambio de estado de un registro.

Se reforzó la administración de usuarios con validaciones de rol y correo, visualización de último ingreso y manejo de clave opcional en edición.

- 2026-03-23: se retiraron del login administrativo los bloques informativos innecesarios y se ajustó la escala visual de login y parametrización para mejorar visualización en 150% de escala y en resoluciones intermedias.

- 2026-03-23: se compactó nuevamente el panel lateral y el panel formulario de parametrización para corregir el exceso de escala y el espacio vacío visible en pantallas con zoom del 150%.


13. Ajustes visuales y operativos del CRUD administrativo
Se evidencia que, el panel lateral del formulario fue corregido para mantener la distribución de campos y acciones sin dejar el contenido en blanco al ejecutar la acción Limpiar.
Se evidencia que, se agregó la acción de eliminar dentro de la tabla y dentro del panel del formulario, manteniendo borrado lógico y trazabilidad de usuario y fecha de borrado.
Se evidencia que, los registros de creación, edición y borrado fueron ajustados para reforzar el llenado de columnas de auditoría y la persistencia correcta de valores binarios en PostgreSQL.
Se evidencia que, se compactó nuevamente la distribución visual del panel lateral y del encabezado para disminuir espacios muertos durante la validación en escritorio con escala alta.


Se ajustó la distribución visual del panel lateral de parametrización para reducir espacios en blanco, retirar la acción eliminar del formulario y homogeneizar la altura visual de tarjetas y secciones CRUD.

- 2026-03-23: se corrigió nuevamente la hoja de estilos de parametrización para retirar espacios muertos del sidebar, encabezado, mensajes, resumen y panel lateral; además, la vista seguridad se redirige temporalmente a parametrización mientras se define su alcance real.
