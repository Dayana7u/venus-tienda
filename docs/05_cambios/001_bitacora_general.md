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


#### Vista pública base de la tienda
- Se agregó `index.php` como punto de entrada del frente comercial separado del panel administrativo.
- Se agregó `backend/app/Views/tienda_publica.php` como vista pública inicial de la tienda.
- Se agregaron `backend/app/Controllers/tienda_publica_controller.php` y `backend/app/Models/tienda_publica_model.class.php` para consultar branding, tema, componentes, parámetros, módulo y menús públicos desde la parametrización ya registrada.
- Se agregaron `backend/public/assets/js/tienda_publica.js`, `tienda_publica_peticiones.js` y `tienda_publica_template.js` respetando separación entre archivo principal, peticiones y templates.
- Se agregó `backend/public/assets/css/tienda_publica.css` para el frente comercial con paleta clara y consumo de variables visuales del tema activo.
- Se dejó la navegación pública leyendo el módulo `TIENDA_PUBLICA`, sus configuraciones activas y el tema `PINK_NUDE` cargado por SQL incremental.

#### Ajuste visual del tema público `PINK_NUDE`
- Se ajustó `backend/public/assets/css/tienda_publica.css` para mejorar el contraste visual de las etiquetas tipo badge y del pie de página del frente comercial.
- Se ajustó `backend/public/assets/js/tienda_publica.js` para consumir nuevas propiedades visuales del tema en `badge` y `footer`, conservando el enfoque parametrizable desde `system.tema_componentes`.
- Se agregó el SQL incremental `database/sql/006_sql_ajuste_visual_tienda_publica.sql` para actualizar colores del footer y registrar configuración visual específica de badges dentro del tema `PINK_NUDE`.

#### Avance visual comercial del tema `PINK_NUDE`
- Se fortaleció `backend/app/Views/tienda_publica.php` con una estructura comercial más completa basada en cards y secciones separadas.
- Se ajustó `backend/public/assets/css/tienda_publica.css` para acercar el frente a una tienda beauty más profesional, con topbar, hero comercial, categorías, destacados, rutina, campañas y testimonios.
- Se ajustó `backend/public/assets/js/tienda_publica.js` para consumir nuevos componentes visuales del tema y nuevas configuraciones del módulo `TIENDA_PUBLICA`.
- Se actualizó `backend/public/assets/js/tienda_publica_template.js` para renderizar bloques de maquillaje, skincare, accesorios, productos destacados, rutina y testimonios desde configuraciones parametrizables.
- Se agregó `database/sql/007_sql_avance_tienda_publica_beauty.sql` para registrar nuevos textos configurables y nuevos componentes visuales del tema `PINK_NUDE` sin crear tablas nuevas.

#### Avance comercial por cards sobre el tema `PINK_NUDE`
- Se amplió `backend/app/Views/tienda_publica.php` con nuevos bloques reutilizables de colecciones y cierre comercial sin introducir modales.
- Se ajustó `backend/public/assets/js/tienda_publica.js` para consumir nuevos bloques del frente público y nuevos componentes visuales del tema.
- Se actualizó `backend/public/assets/js/tienda_publica_template.js` para renderizar colecciones curadas, productos con precio comparativo, contacto destacado y cierre comercial parametrizable.
- Se fortaleció `backend/public/assets/css/tienda_publica.css` con estilos para nuevas cards, CTA de contacto, bloque final comercial y pie de página con columnas.
- Se agregó `database/sql/008_sql_avance_tienda_publica_cards.sql` para registrar nuevas configuraciones del módulo `TIENDA_PUBLICA` y nuevos componentes del tema `PINK_NUDE`.


## Avance 009 - tienda pública catálogo real
Se evidencia que, el frente público continuó sobre PINK_NUDE con una organización más clara por bloques y con una salida más cercana a catálogo real.

### Novedades y Modificaciones
- Se agregó `database/sql/009_sql_avance_tienda_publica_catalogo_real.sql`.
- Se ajustó `backend/app/Views/tienda_publica.php` para incorporar nuevas secciones del frente público.
- Se reorganizó `backend/public/assets/js/tienda_publica.js` por bloques funcionales de renderización.
- Se amplió `backend/public/assets/js/tienda_publica_template.js` con cards reutilizables de líneas, más vendidos y FAQ.
- Se ajustó `backend/public/assets/css/tienda_publica.css` para los nuevos bloques del catálogo.

- Se separa la tienda pública en módulos y rutas limpias (`/`, `/catalogo/`, `/ofertas/`, `/producto/`, `/carrito/`, `/contacto/`) y se deja `/admin/` aparte del flujo comercial.
- Se agrega carrito funcional en sesión para permitir agregar, actualizar y eliminar productos sin crear estructura nueva de base de datos.

## Corrección de continuidad del frente público

- Se restablece la tienda pública sobre rutas y vistas separadas (`/`, `/catalogo/`, `/ofertas/`, `/producto/`, `/carrito/`, `/contacto/`) para evitar que el frente vuelva a una sola vista.
- Se conserva `/admin/` para parametrización y seguridad, y `/admin/tienda/` como acceso independiente para la administración comercial de la tienda.
- Se integran nuevamente los archivos del panel de tienda sobre la base multivista ya funcional, manteniendo cargue de imágenes para categorías y productos.

## Avance multivista tienda pública y carrito lateral
Se evidencia que, se retoma como base válida el frente multivista de la tienda pública, manteniendo separadas las vistas de inicio, catálogo, producto, carrito, ofertas y contacto, sin volver a concentrar la operación comercial en una sola página.

Se evidencia que, el carrito lateral se conserva como comportamiento global del frente público y se mejora para operar por peticiones asíncronas, con apertura desde el encabezado, actualización de cantidades, eliminación de líneas, resumen económico y alerta visible al agregar productos.

Se evidencia que, el catálogo y el detalle de producto quedan preparados para consumir información de `public.productos`, `public.categorias` y `public.producto_imagenes` cuando existan registros en base de datos, usando el catálogo estático solo como respaldo mientras se completa la carga administrativa.

Novedades y Modificaciones
- Se ajustó `backend/app/Models/tienda_catalogo_base_model.class.php` para consultar productos, precios, descuentos, stock e imágenes desde base de datos y calcular el estado del carrito con subtotal, ahorro, envío y total.
- Se ajustó `backend/app/Controllers/tienda_carrito_controller.php` para responder por AJAX sin recargar la página al agregar, actualizar o eliminar productos del carrito lateral.
- Se ajustaron `backend/app/Views/tienda_inicio.php`, `backend/app/Views/tienda_catalogo.php`, `backend/app/Views/tienda_producto.php`, `backend/app/Views/tienda_carrito.php`, `backend/app/Views/tienda_ofertas.php` y `backend/app/Views/tienda_contacto.php` para mantener la navegación por módulos y reutilizar el carrito lateral en todo el frente público.
- Se ajustó `backend/app/Views/tienda/tienda_helper.php` para centralizar encabezado, cards de producto, drawer del carrito y carga común de scripts del frente comercial.
- Se agregaron `backend/public/assets/js/tienda_store.js`, `backend/public/assets/js/tienda_store_peticiones.js` y `backend/public/assets/js/tienda_store_template.js` para manejar la interacción global del carrito lateral y las alertas del frente comercial.
- Se ajustó `backend/public/assets/css/tienda_publica.css` para mejorar hero de inicio, cards de producto, detalle, carrito lateral y vista dedicada de carrito.


## 2026-03-24 - Avance v12
- Se mantiene la base multivista del ecommerce.
- Se mejora el carrito lateral existente sin regresarlo a otra implementación.
- Se separa el panel de tienda en submódulos visuales: resumen, categorías, productos e imágenes.
- No se agregan tablas ni SQL nuevos en este bloque.


## 2026-03-24 - Avance v13
Se evidencia que, el frente de administracion comercial de la tienda se amplia con una base operativa mas cercana a un ecommerce real, separando dashboard, pedidos, clientes y ventas del bloque de catalogo ya existente.

Novedades y Modificaciones
- Se agrega `database/sql/013_sql_tienda_admin_operacion.sql` para crear `public.clientes_tienda`, `public.clientes_tienda_direcciones`, `public.pedidos_tienda` y `public.pedido_tienda_detalles`, junto con indices, permisos base y registros iniciales de prueba.
- Se ajusta `backend/app/Models/tienda_admin_model.class.php` para consultar resumen comercial, clientes, pedidos, ingresos, descuentos y productos top sin romper el catalogo ni las imagenes ya registradas.
- Se ajusta `backend/app/Views/tienda_admin_dashboard.php` para mostrar indicadores y accesos directos a pedidos, clientes, ventas y catalogo.
- Se agregan `backend/app/Views/tienda_admin_clientes.php`, `backend/app/Views/tienda_admin_pedidos.php` y `backend/app/Views/tienda_admin_ventas.php` como submodulos propios del panel tienda.
- Se agregan las rutas `backend/admin/tienda/clientes/index.php`, `backend/admin/tienda/pedidos/index.php` y `backend/admin/tienda/ventas/index.php` para mantener el panel separado por modulo.
- Se ajustan `backend/public/assets/js/tienda_admin.js` y `backend/public/assets/js/tienda_admin_template.js` para renderizar clientes, pedidos, indicadores de ventas y productos top desde el panel tienda.
- Se ajusta `backend/public/assets/css/tienda_admin.css` para mejorar la presentacion del dashboard comercial con cards, filas operativas y bloques de resumen.


## 2026-03-24 - Avance v14
Se evidencia que, el panel administrativo de tienda se reorganiza visualmente hacia una experiencia más cercana a un dashboard ecommerce real, manteniendo la separación por módulos ya construida y sin devolver la operación a una sola vista.

Novedades y Modificaciones
- Se ajusta `backend/app/Views/tienda_admin/tienda_admin_helper.php` para centralizar una plantilla administrativa con sidebar, topbar, búsqueda general y sesión activa sin mezclarla con parametrización y seguridad.
- Se ajusta `backend/public/assets/css/tienda_admin.css` para llevar el panel tienda a una apariencia más profesional, con navegación lateral, cards métricas, paneles analíticos y listados comerciales en la misma línea visual del tema `PINK_NUDE`.
- Se ajusta `backend/public/assets/js/tienda_admin.js` para soportar búsqueda en el módulo actual y renderización controlada del dashboard, clientes, pedidos, ventas, categorías, productos e imágenes sin romper formularios ni listados existentes.
- Se ajusta `backend/public/assets/js/tienda_admin_template.js` para imprimir cards métricas, paneles de resumen y listados operativos más cercanos a un panel ecommerce real.
- Se ajustan `backend/app/Views/tienda_admin_dashboard.php`, `backend/app/Views/tienda_admin_categorias.php`, `backend/app/Views/tienda_admin_productos.php`, `backend/app/Views/tienda_admin_imagenes.php`, `backend/app/Views/tienda_admin_clientes.php`, `backend/app/Views/tienda_admin_pedidos.php` y `backend/app/Views/tienda_admin_ventas.php` para mantener submódulos separados y alineados a la nueva plantilla visual del panel tienda.
- En este bloque no se agregan tablas ni SQL nuevos.


## 2026-03-24 - Ajuste de estabilidad V15
Se corrigieron errores del frente público y del panel de tienda asociados al uso de funciones multibyte no disponibles en el entorno PHP (`mb_substr`, `mb_strtoupper`, `mb_strlen`, `mb_strpos`, `mb_strtolower`). También se corrigió la redirección del login de administración de tienda para enviar a `/admin/tienda/dashboard/` cuando la sesión ya está activa, evitando bucles de redirección.

## 2026-03-24 - Ajuste funcional V16
Novedades y Modificaciones
- Se ajusta `backend/app/Views/tienda_admin/tienda_admin_helper.php` para compactar la cabecera lateral del panel tienda y evitar desbordes visuales en la columna izquierda.
- Se ajustan `backend/app/Views/tienda_admin_categorias.php`, `backend/app/Views/tienda_admin_productos.php`, `backend/app/Views/tienda_admin_imagenes.php` y `backend/app/Views/tienda_admin_pedidos.php` para dejar formularios con obligatoriedad visible, edición en la misma vista y listados sin bloques repetidos.
- Se ajusta `backend/public/assets/js/tienda_admin.js` y `backend/public/assets/js/tienda_admin_template.js` para soportar edición, inactivación y acciones rápidas de pedidos desde cards.
- Se ajusta `backend/app/Models/tienda_admin_model.class.php` y `backend/app/Controllers/tienda_admin_controller.php` para permitir actualización de categorías, productos, imágenes y pedidos sin SQL nuevo.
- Se ajusta `backend/app/Models/tienda_catalogo_base_model.class.php` y `backend/public/assets/css/tienda_publica.css` para usar imágenes de prueba, mejorar el catálogo y ampliar visualmente el footer.
- Se agregan imágenes de prueba en `backend/public/uploads/tienda/demo/` para apoyar pruebas del catálogo y del panel tienda mientras se carga material definitivo.
