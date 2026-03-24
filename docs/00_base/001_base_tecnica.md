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


## Vista pública base separada del panel administrativo
Se evidencia que, se agregó un frente comercial inicial independiente del acceso administrativo, manteniendo la separación entre la vista pública de la tienda y el panel de parametrización y seguridad.
Se evidencia que, el nuevo frente consulta branding, tema, tokens, componentes, parámetros, módulo y menús públicos desde las tablas ya parametrizadas del esquema system, sin modificar su estructura.
Se evidencia que, el punto de entrada público quedó en `index.php`, mientras el acceso administrativo continúa en `backend/index.php`, conservando rutas distintas para cada capa.
Se evidencia que, la vista pública quedó preparada para reutilizar identidad visual y configuraciones activas en otras tiendas sin rehacer la lógica base.

## Ajuste visual incremental del frente público
Se evidencia que, el frente público continúa separado del panel administrativo y mantiene la identidad visual parametrizable desde base de datos.
Se evidencia que, se ajustó el contraste de las etiquetas visuales y del pie de página del tema `PINK_NUDE`, mejorando legibilidad sin alterar la estructura base del módulo público.
Se evidencia que, el ajuste se soporta sobre CSS, JavaScript y un SQL incremental de actualización sobre `system.tema_componentes`, sin crear tablas nuevas ni romper la parametrización ya construida.


## Avance comercial actual de la tienda pública
Se evidencia que, el frente comercial del tema `PINK_NUDE` dejó la base inicial informativa y ahora avanza hacia una composición más cercana a una tienda beauty profesional.
Se evidencia que, la portada pública ya queda organizada por topbar, hero, categorías, destacados, rutina, campañas, testimonios, contacto y footer, manteniendo el enfoque por cards y sin introducir modales.
Se evidencia que, este ajuste continúa soportado por `system.modulo_configuraciones` y `system.tema_componentes`, de modo que la evolución visual se mantiene dentro de la parametrización ya existente.
