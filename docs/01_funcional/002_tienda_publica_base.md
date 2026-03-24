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
