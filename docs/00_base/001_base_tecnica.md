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
4. con sesión activa se redirecciona a `backend/app/Views/parametrizacion.php`
5. la salida de sesión se hace desde `backend/cerrar_sesion.php`

## Observaciones
- las tablas del esquema `system` quedan cerradas a nivel estructural en esta etapa
- la validación visual ya puede comenzar desde el flujo de login
- el avance siguiente debe centrarse en pruebas y ajustes funcionales del acceso y de parametrización
