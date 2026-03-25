# Tienda pública base

## Estado visual actual
Se evidencia que, el frente público de la tienda ya consume branding, menús, módulo público, tema activo, tokens y componentes visuales desde la parametrización existente.

## Ajuste aplicado sobre el tema `PINK_NUDE`
Novedades y Modificaciones
Se ajustó el contraste de las etiquetas visuales tipo badge para evitar pérdida de lectura sobre fondos rosados claros.
Se ajustó el pie de página con una base rosa nude de mayor presencia visual y mejor contraste para textos y enlaces.
Se mantuvo el enfoque visual por tarjetas, sin introducir modales en el frente comercial.

## Alcance del ajuste
- No se crean tablas nuevas.
- No se modifica la estructura base de `system`.
- El ajuste se soporta sobre `system.tema_componentes`, `backend/public/assets/css/tienda_publica.css` y `backend/public/assets/js/tienda_publica.js`.
- El tema `PINK_NUDE` continúa siendo la base para los siguientes avances del catálogo comercial.


## Avance visual beauty sobre el mismo tema
Novedades y Modificaciones
Se amplió la portada pública para manejar topbar, hero comercial, categorías destacadas, productos destacados, rutina sugerida, campañas y testimonios.
Se mantuvo el enfoque por cards como patrón principal del frente comercial.
Se dejó la lectura del contenido principal desde `system.modulo_configuraciones`, manteniendo textos visibles y mensajes del frente dentro de la parametrización existente.
Se adicionó `007_sql_avance_tienda_publica_beauty.sql` para sembrar el contenido base del frente beauty y nuevos componentes visuales del tema `PINK_NUDE`.


## Avance comercial por cards sobre el tema `PINK_NUDE`
Novedades y Modificaciones
Se agregó un bloque de colecciones curadas para organizar campañas visuales sin salir del enfoque parametrizable actual.
Se ajustaron las tarjetas de productos destacados para mostrar precio visible, precio comparativo y acción comercial base.
Se agregó un bloque principal de contacto y un bloque final de cierre comercial, manteniendo el patrón visual por cards.
Se adicionó `008_sql_avance_tienda_publica_cards.sql` para sembrar textos y componentes visuales nuevos sobre `TIENDA_PUBLICA` y `PINK_NUDE`.


## Avance catálogo visual v5
Se evidencia que, la portada pública ya incorpora bloques de líneas de producto, más vendidos y preguntas frecuentes, manteniendo el enfoque beauty para maquillaje, skincare y accesorios.

### Novedades y Modificaciones
- Se agregó un bloque de líneas principales con tres cards: maquillaje, skincare y accesorios.
- Se agregó un bloque de más vendidos con seis cards y foco comercial.
- Se agregó un bloque FAQ por cards para dudas frecuentes de compra.
- Se mantuvo la estructura reusable por tema y por configuraciones del módulo TIENDA_PUBLICA.

## Avance por módulos de tienda pública

Se reemplazó la vista única de tienda por rutas y módulos separados: inicio, catálogo, ofertas, detalle, carrito y contacto. El carrito quedó funcional con manejo en sesión y el frente comercial sigue consumiendo branding, menús y tema activo desde la parametrización existente.

## Ajuste funcional del frente comercial multivista
Se evidencia que, el frente comercial mantiene las vistas separadas de inicio, catálogo, detalle, carrito, ofertas y contacto, y que el carrito lateral queda disponible desde cualquier vista para agregar, actualizar y eliminar productos sin recargar la página.

Se evidencia que, el comportamiento del carrito lateral ya contempla total de productos, subtotal, ahorro, envío y total, además de alerta visible cuando un producto es agregado correctamente.

Se evidencia que, la visualización del producto ya contempla imagen principal y galería adicional cuando existan registros en `public.producto_imagenes`, de modo que la operación del panel `/admin/tienda/` pueda reflejarse directamente en el frente público.


## Panel tienda - avance de operacion comercial
Se agrega una capa administrativa separada para la tienda con enfoque en operacion comercial. Esta capa incorpora dashboard, pedidos, clientes y ventas como vistas propias, manteniendo categorias, productos e imagenes como modulos independientes.

La base funcional queda lista para evolucionar luego a autenticacion de clientes, checkout y gestion de estados de compra, sin mezclar estos procesos con parametrizacion ni con seguridad administrativa general.


## 2026-03-24 - Ajuste de estabilidad V15
Se corrigieron errores del frente público y del panel de tienda asociados al uso de funciones multibyte no disponibles en el entorno PHP (`mb_substr`, `mb_strtoupper`, `mb_strlen`, `mb_strpos`, `mb_strtolower`). También se corrigió la redirección del login de administración de tienda para enviar a `/admin/tienda/dashboard/` cuando la sesión ya está activa, evitando bucles de redirección.

## 2026-03-24 - Ajuste funcional V16
Se corrige la presentación del catálogo para alinear mejor el bloque de filtros y mejorar el ancho útil del footer.
Se agrega respaldo visual con imágenes de prueba locales para categorías y productos cuando el material cargado desde la administración comercial aún no exista.

## 2026-03-24 - Ajuste funcional V17
Se corrige la ubicación del bloque de filtros en la vista de catálogo para mantenerlo alineado hacia el extremo derecho del encabezado del módulo.
Se amplía el footer para distribuir mejor navegación, categorías y datos de atención sin concentrar el contenido únicamente en el extremo izquierdo.
Se mantiene el comportamiento multivista del frente comercial y se preserva el consumo de categorías y productos activos desde la base de datos.

## 2026-03-24 - Panel tienda con permisos operativos
Se agrega una capa de permisos funcionales para el panel `/admin/tienda/`, de forma que dashboard, pedidos, clientes, ventas, categorías, productos e imágenes puedan controlarse por rol desde la parametrización administrativa.

Se agrega también edición de clientes desde el panel tienda, permitiendo actualizar nombres, apellidos, correo, celular y ciudad principal sin abrir otro módulo adicional.

- El panel tienda incorpora módulo de auditoría y validaciones para impedir inactivar o eliminar categorías y productos cuando comprometen la operación comercial o la trazabilidad histórica.

## Bloque checkout y pasarela base
Se evidencia que, el frente público incorpora la vista `/checkout/` para registrar los datos del comprador, la dirección de entrega y el método de pago seleccionado desde el carrito.

Novedades y Modificaciones
Se agregó el formulario de checkout con datos de comprador, dirección, observación de entrega y selección de método de pago.
Se habilitaron los métodos base PSE, Tarjeta y Contra entrega dentro del mismo flujo de checkout.
Se dejó la persistencia del pedido y del pago sobre las tablas operativas del ecommerce para que la operación quede visible en el panel administrativo de tienda.
Se ajustó el carrito para redirigir al checkout desde la vista dedicada y desde el drawer lateral.

- Checkout: se agregan validaciones por PSE, tarjeta y contra entrega, con referencia de pago y seguimiento administrativo de pagos.


Se evidencia que, el checkout se dividió en dos vistas: /checkout/ para datos del pedido y /checkout/pago/ para método de pago, con adaptación del formulario según PSE, tarjeta o contra entrega y con resumen dinámico de productos del carrito.


## v26 · Checkout parametrizable, soporte y comprobante
Se evidencia que, el bloque de checkout y pago se ajusta para consumir configuración visible desde `system.modulo_configuraciones`, de modo que etiquetas, placeholders, obligatoriedad, mensajes comerciales y acciones de confirmación puedan cambiarse sin tocar la estructura base del frente público.

Novedades y Modificaciones
Se amplió la parametrización del checkout para comprador, entrega, PSE, tarjeta y contra entrega.
Se corrigió el resumen del checkout y del pago para que cada línea del pedido muestre el total real y no un valor en cero cuando proviene del carrito en sesión.
Se agregó el comprobante público del pedido en `/pedido/comprobante/` con vista lista para impresión y soporte comercial.
Se ajustó `/contacto/` para recibir datos del pedido y dejar acciones directas de WhatsApp, correo o llamada cuando el usuario llega desde la confirmación.
Se amplió el margen útil de `/checkout/` y `/checkout/pago/`, se estilizaron las tarjetas de métodos de pago con íconos visibles y se reorganizó el panel de filtros del catálogo.


## Nuevo tema `VENUS`

Se agregó `019_sql_tema_venus_base.sql` para crear el nuevo tema `VENUS` sobre la misma base funcional de `PINK_NUDE`, conservando ambos temas dentro de la parametrización.

El nuevo tema deja activa la paleta `#DDD4E7`, `#BFAFD0`, `#D4B6CA`, `#F3D6D3`, `#F5CFC6` y ajusta la identidad comercial visible de la tienda pública a `VENUS`.


Tema VENUS parametrizable
Se evidencia que, el frente visual VENUS ahora se aplica mediante archivos CSS por tema y no únicamente por tokens guardados en base de datos.
Se evidencia que, la tienda pública carga el archivo del tema activo desde backend/public/assets/css/themes/tienda/, mientras el panel administrativo lo hace desde backend/public/assets/css/themes/admin/.
Se evidencia que, esta estructura conserva la parametrización visual y permite mantener temas paralelos, como pink y venus, sin mezclar estilos ni sobrescribir la base anterior.


Home VENUS
Se evidencia que, la página principal del tema VENUS se ajusta a una composición editorial con hero visual, navegación superior liviana, categorías circulares, productos destacados compactos, bloques promocionales y footer integrado dentro del mismo contenedor visual.
Se evidencia que, este ajuste no modifica la lógica funcional de carrito, catálogo, producto, checkout ni pedido; únicamente aterriza la capa visual de la portada para alinearla con la guía enviada para escritorio y con soporte responsive para móvil.

## Ajuste visual integral tema VENUS
Se evidencia que, el homepage VENUS ahora consume productos, imágenes y líneas reales del catálogo, evitando tarjetas fijas que no correspondan a la operación comercial visible.

Novedades y Modificaciones
Se dejó el hero principal preparado para tomar un producto configurado por slug o, en su defecto, el primer destacado activo.
Se dejó el bloque de destacados preparado para seleccionar hasta cuatro productos por slug desde `system.modulo_configuraciones` o completar con destacados reales del catálogo.
Se dejó el homepage preparado para consumir banner principal y banner secundario parametrizables, manteniendo respaldo visual cuando aún no exista material cargado.
Se unificó la identidad visual VENUS sobre catálogo, detalle, carrito, checkout, pago y panel tienda para continuar los siguientes ajustes sobre una misma base estética.
