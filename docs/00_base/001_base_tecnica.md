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


## Avance técnico tienda pública por cards
- La vista `backend/app/Views/tienda_publica.php` ahora separa hero, beneficios, colecciones, categorías, destacados, rutina, campañas, testimonios, contacto, cierre comercial y footer.
- El archivo `backend/public/assets/js/tienda_publica_template.js` conserva la lectura del contenido desde `system.modulo_configuraciones` y amplía el renderizado a nuevos bloques reutilizables.
- El archivo `backend/public/assets/js/tienda_publica.js` aplica nuevos componentes visuales (`collection.card`, `newsletter`, `contact.highlight`) consumidos desde `system.tema_componentes`.


14. Avance visual y funcional del frente público sobre PINK_NUDE
Se evidencia que, el frente público continúa creciendo sobre el mismo tema PINK_NUDE y sobre la parametrización ya existente, sin crear tablas nuevas y manteniendo la separación total frente al acceso administrativo.
Se evidencia que, en esta etapa se reorganizó la impresión del frente por bloques funcionales para mejorar la lectura del código y dejar el crecimiento del catálogo más ordenado por secciones reutilizables.
Novedades y Modificaciones
Se agregó un bloque de líneas principales del catálogo para maquillaje, skincare y accesorios.
Se agregó un bloque de más vendidos con seis cards de producto con precio, precio anterior, rating y mensaje logístico.
Se agregó un bloque de preguntas frecuentes para reforzar confianza de compra dentro del mismo frente público.
Se ajustó tienda_publica.js para separar la renderización por header, bloques principales, bloques comerciales, bloques de relación y footer.
Se ajustó tienda_publica_template.js para imprimir nuevas cards reutilizables del catálogo y mantener el enfoque sin modales.
Se agregó el script database/sql/009_sql_avance_tienda_publica_catalogo_real.sql para sembrar configuraciones y componentes visuales requeridos por estos nuevos bloques.
