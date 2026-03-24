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
