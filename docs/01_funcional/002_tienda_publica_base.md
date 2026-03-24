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
