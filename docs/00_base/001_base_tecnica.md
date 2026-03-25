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

## Avance tienda pública por módulos

Se separó el frente comercial en rutas y vistas independientes: `/`, `/catalogo/`, `/ofertas/`, `/producto/`, `/carrito/` y `/contacto/`. El acceso `/admin/` se mantiene aparte. En esta etapa el carrito se maneja en sesión para avanzar el flujo comercial sin crear nuevas tablas.

## Corrección de continuidad del frente público
- El frente comercial vuelve a quedar separado por módulos y rutas independientes.
- El acceso administrativo de tienda se mantiene totalmente aparte del acceso de parametrización y seguridad.
- La base conserva soporte para categorías, productos e imágenes desde la administración de tienda.

11. Continuidad del frente comercial multivista
Se evidencia que, el frente público de la tienda continúa sobre una estructura multivista compuesta por inicio, catálogo, producto, carrito, ofertas y contacto, manteniendo el panel `/admin/` para parametrización y seguridad, y `/admin/tienda/` para la operación comercial independiente.
Se evidencia que, el carrito lateral queda como componente reutilizable del frente público y no reemplaza la vista `/carrito/`, sino que la complementa para sostener una experiencia de compra rápida sin perder la separación de módulos.
Se evidencia que, el catálogo y el detalle del producto quedan preparados para consumir registros administrativos reales de categorías, productos e imágenes desde base de datos, utilizando la data estática únicamente como respaldo de continuidad cuando la operación comercial aún no haya cargado información.


## Avance v12 - separación de admin tienda
- El panel `/admin/tienda/` se mantiene independiente de parametrización y seguridad.
- La administración comercial ya no se concentra en una sola vista; queda separada por submódulos `dashboard`, `categorias`, `productos` e `imagenes`, reutilizando el mismo controlador/modelo y manteniendo JS externo por módulo administrativo.
- El carrito lateral del frente público se conserva como componente global y se mejora visualmente sin reemplazar la vista `/carrito/`.


## Panel administrativo de tienda - operacion comercial
El panel administrativo de tienda continua separado del acceso de parametrizacion y seguridad del proyecto base. Sobre esta linea se agregan submodulos propios para dashboard comercial, pedidos, clientes y ventas, conservando el acceso en `/admin/tienda/` y manteniendo la gestion de catalogo, productos e imagenes en rutas independientes por modulo.

A nivel de datos, la operacion comercial queda apoyada en tablas propias del frente de tienda para clientes, direcciones, pedidos y detalle de pedidos, de modo que el panel ya pueda mostrar informacion de ventas y no solo configuracion de catalogo.


## Panel administrativo de tienda - dashboard modular visual
Se evidencia que, el panel administrativo de tienda continúa sobre rutas separadas por módulo (`dashboard`, `pedidos`, `clientes`, `ventas`, `categorias`, `productos`, `imagenes`) y se fortalece con una plantilla administrativa propia, distinta de parametrización y seguridad.
Se evidencia que, la navegación lateral, la búsqueda general, los indicadores métricos y los paneles de resumen se implementan sobre la estructura ya existente del panel tienda, sin concentrar la operación comercial en una sola vista.
Se evidencia que, este ajuste se soporta en `tienda_admin_helper.php`, `tienda_admin.css`, `tienda_admin.js` y `tienda_admin_template.js`, conservando la separación entre vista, controlador, modelo y archivos JavaScript externos.


## 2026-03-24 - Ajuste de estabilidad V15
Se corrigieron errores del frente público y del panel de tienda asociados al uso de funciones multibyte no disponibles en el entorno PHP (`mb_substr`, `mb_strtoupper`, `mb_strlen`, `mb_strpos`, `mb_strtolower`). También se corrigió la redirección del login de administración de tienda para enviar a `/admin/tienda/dashboard/` cuando la sesión ya está activa, evitando bucles de redirección.

## 2026-03-24 - Ajuste funcional V16
Se evidencia que, el panel tienda continúa sobre la base modular ya existente y se corrige la distribución de elementos para no repetir bloques de resumen en categorías, productos, imágenes ni pedidos.
Se evidencia que, se fortalecen los formularios del panel tienda con edición sobre los mismos registros de categorías, productos e imágenes, manteniendo el flujo por cards y sin regresar a modales.
Se evidencia que, se incorporan rutas locales de imágenes de prueba para categorías y productos, con respaldo visual automático cuando la base de datos aún no tenga material cargado.

- Se añade la tabla `public.tienda_admin_auditoria` como soporte de trazabilidad del panel tienda y se agregan permisos de auditoría y borrado lógico de categorías.

Checkout y pago base
Se evidencia que, el proyecto incorpora una etapa de checkout separada del carrito, con persistencia de cliente, dirección, pedido y pago para soportar la operación comercial del ecommerce y dejar lista la integración posterior con pasarela real.

## Checkout y pasarela
- El frente público usa `/checkout/` para datos de comprador y `/checkout/pago/` para el cobro.
- La integración de pago se prepara con servicio `pasarela_wompi_service.class.php` y configuración `backend/config/pasarela_wompi.php`.
- Para activar pagos reales se deben definir `WOMPI_ENABLED`, `WOMPI_PUBLIC_KEY`, `WOMPI_PRIVATE_KEY`, `WOMPI_INTEGRITY_KEY` y `APP_BASE_URL`.

## v25
- Se corrige el flujo de checkout y pago sobre la base v24.
- Se agrega envío correcto del titular de tarjeta hacia el backend para evitar bloqueo en el cobro con tarjeta.
- Se endurecen validaciones de celular, teléfono, documentos, titular, fecha y CVV.
- Se mantiene modal bloqueante para confirmaciones/validaciones y se agrega toast informativo flotante para avisos no críticos.
- Se ajustan campos numéricos y experiencia visual del formulario de pago.
