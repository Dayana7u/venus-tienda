# Base técnica del proyecto

## Entorno oficial
El proyecto tienda_virtual se trabaja sobre Docker + Ubuntu + Git como flujo oficial.

## Base de datos
- motor: PostgreSQL
- esquema transaccional y de seguridad: `public`
- esquema de parametrización: `system`

## Conexión
La conexión centralizada se maneja desde:

- `backend/config/configdb.php`

## Punto de entrada actual
Para iniciar validación en navegador se habilitó:

- `backend/index.php`

## Flujo actual
1. el navegador entra por `backend/index.php`
2. si no existe sesión activa se redirecciona a `backend/app/Views/login.php`
3. el login valida el usuario contra `public.usuarios`
4. si la sesión administrativa existe y sigue activa en `public.usuarios_sesiones`, el acceso se conserva
5. con sesión activa se puede navegar entre `parametrizacion.php` y `seguridad.php`
6. la salida de sesión se hace desde `backend/cerrar_sesion.php`

## Observaciones
- las tablas del esquema `system` quedan cerradas a nivel estructural en esta etapa
- parametrización se mantiene como bloque CRUD base sin ampliar alcance funcional
- el frente actual se centra en seguridad administrativa, sesiones y claves encriptadas
