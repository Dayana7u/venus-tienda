# Guia tema VENUS premium editorial

## Objetivo
Definir una guia concreta para implementar el tema VENUS en el frente publico del ecommerce usando variables CSS y utilidades Tailwind, manteniendo la parametrizacion existente del proyecto.

## Paleta oficial
- `#DDD4E7` `venus-lilac-100`: fondos alternos, contenedores suaves, tarjetas editoriales.
- `#BFAFD0` `venus-lilac-300`: CTA principal, bordes suaves, hover, stepper activo.
- `#D4B6CA` `venus-mauve-300`: CTA secundario, chips, acentos de seccion.
- `#F3D6D3` `venus-blush-200`: hero, banners destacados, secciones promocionales.
- `#F5CFC6` `venus-peach-200`: badges de descuento, highlights, acentos de oferta.
- `#685666` `venus-graphite-700`: headings, texto principal, iconos activos, footer oscuro.
- `#8A7A88` `venus-lavender-500`: descripciones, labels, placeholders, texto secundario.
- `#FFFFFF`: superficies elevadas, inputs, modales, cards.
- Fondo general sugerido: `#F5F2F8`.

## Tipografia
- Headings y display: `Playfair Display`, serif.
- UI y texto base: `Inter`, sans-serif.

## Variables CSS sugeridas
```css
:root {
  --venus-page: #F5F2F8;
  --venus-surface: #FFFFFF;
  --venus-primary: #BFAFD0;
  --venus-secondary: #D4B6CA;
  --venus-soft: #DDD4E7;
  --venus-blush: #F3D6D3;
  --venus-peach: #F5CFC6;
  --venus-text: #685666;
  --venus-text-soft: #8A7A88;
  --venus-border: #BFAFD0;
  --venus-footer: #685666;
  --venus-footer-text: #F5F2F8;
  --venus-shadow: 0 18px 42px rgba(104, 86, 102, 0.10);
  --venus-radius-pill: 999px;
  --venus-radius-card: 28px;
}
```

## Tailwind theme extend sugerido
```js
export default {
  theme: {
    extend: {
      colors: {
        venus: {
          page: 'var(--venus-page)',
          surface: 'var(--venus-surface)',
          primary: 'var(--venus-primary)',
          secondary: 'var(--venus-secondary)',
          soft: 'var(--venus-soft)',
          blush: 'var(--venus-blush)',
          peach: 'var(--venus-peach)',
          text: 'var(--venus-text)',
          muted: 'var(--venus-text-soft)',
          border: 'var(--venus-border)',
          footer: 'var(--venus-footer)',
          footerText: 'var(--venus-footer-text)'
        }
      },
      fontFamily: {
        display: ['Playfair Display', 'serif'],
        body: ['Inter', 'sans-serif']
      },
      boxShadow: {
        venus: 'var(--venus-shadow)'
      },
      borderRadius: {
        venus: 'var(--venus-radius-card)',
        pill: 'var(--venus-radius-pill)'
      }
    }
  }
}
```

## Reglas globales
- Fondo general del sitio: `bg-venus-page`.
- Cards, modales, drawer y resumenes: `bg-venus-surface shadow-venus rounded-venus border border-venus-border/40`.
- Heading principal: `font-display text-venus-text`.
- Texto secundario: `text-venus-muted`.
- CTA principal: `bg-venus-primary text-white hover:opacity-95 rounded-pill`.
- CTA secundario: `bg-venus-secondary text-white hover:opacity-95 rounded-pill`.
- Inputs: `bg-white border border-venus-border text-venus-text placeholder:text-venus-muted rounded-pill`.
- Badges: `bg-venus-peach text-venus-text rounded-pill`.
- Hover de cards: levantar 1px o cambiar opacidad sin salir de la paleta.

## Home
### Fondo y estructura
- Fondo general: `#F5F2F8`.
- Hero editorial: bloque izquierdo `#F3D6D3`, bloque visual derecho mezcla `#DDD4E7` y blanco.
- Secciones alternas: `#FFFFFF` y `#DDD4E7` muy aclarado.

### Elementos
- Header: fondo `rgba(245,242,248,0.96)`, texto `#685666`, hover `#BFAFD0`.
- Buscador: fondo blanco, borde `#BFAFD0`, placeholder `#8A7A88`.
- Cards de linea y destacados: fondo blanco, borde suave `#BFAFD0`, sombra suave.
- CTA principal hero: `#BFAFD0` con texto blanco.
- CTA secundario hero: `#D4B6CA` con texto blanco.
- Chips editoriales: `#F5CFC6` con texto `#685666`.

## Catalogo
### Fondo y layout
- Contenedor general: `#F5F2F8`.
- Panel de filtros: fondo blanco con borde `#BFAFD0`.
- Grid de productos: cards blancas sobre fondo claro.

### Elementos
- Breadcrumb: texto `#8A7A88`.
- Toolbar y ordenamiento: fondo blanco, bordes `#BFAFD0`.
- Filtros activos: badge `#D4B6CA` con texto blanco o `#685666` segun contraste.
- Precio actual: `#685666`.
- Precio tachado: `#8A7A88`.
- Badge de descuento: `#F5CFC6` con texto `#685666`.
- Rating: dorado suave `#C9A24F`.

## Producto
### Fondo y layout
- Galeria: superficie blanca con fondo interno `#DDD4E7` muy aclarado.
- Panel de informacion: card blanca con sombra suave.

### Elementos
- Nombre producto: `#685666`, `Playfair Display`.
- Subtexto, beneficios, ingredientes: `#8A7A88`.
- Variantes: chips blancos con borde `#BFAFD0`; activa `#BFAFD0` con texto blanco.
- Cantidad: input blanco con borde `#BFAFD0`.
- Boton agregar: `#BFAFD0`.
- Boton favoritos o accion secundaria: `#D4B6CA`.
- Bloques de envio y reseñas: fondo `#FFFFFF`, borde suave `#DDD4E7`.

## Carrito
### Fondo y layout
- Fondo pagina: `#F5F2F8`.
- Lista de items y resumen lateral: cards blancas con sombra suave.

### Elementos
- Miniaturas: fondo `#DDD4E7` aclarado.
- Nombre producto: `#685666`.
- Variante y metadata: `#8A7A88`.
- Campo cupon: input blanco con borde `#BFAFD0`.
- Boton continuar checkout: `#BFAFD0`.
- Boton seguir comprando: `#D4B6CA`.
- Separadores: `#BFAFD0` con opacidad baja.

## Checkout
### Stepper
- Inactivo: fondo blanco, borde `#BFAFD0`, texto `#8A7A88`.
- Activo: fondo `#BFAFD0`, texto blanco.
- Completado: puede usar `#D4B6CA` con texto blanco.

### Paso contacto y envio
- Formulario: card blanca.
- Labels: `#8A7A88`.
- Inputs: blanco + borde `#BFAFD0`.
- Resumen lateral: blanco con badges `#F5CFC6`.
- Metodos de entrega: cards blancas con borde `#BFAFD0`; activa con fondo `#DDD4E7` muy suave.

### Paso pago
- Selector de metodo: cards blancas con borde `#BFAFD0`.
- Metodo activo: fondo `rgba(191,175,208,0.14)`.
- Mensajes informativos: `#F3D6D3` o `#DDD4E7` suave segun prioridad.
- Boton pagar: `#BFAFD0`.

## Confirmacion
### Fondo y layout
- Fondo general claro `#F5F2F8`.
- Card principal blanca con acentos blush.

### Elementos
- Estado exitoso: bloque superior con fondo `#F3D6D3` o gradiente `#DDD4E7 -> #F3D6D3`.
- Numero de pedido y total: `#685666`.
- Resumen del pedido: cards blancas.
- CTA ver recibo: `#D4B6CA`.
- CTA seguir comprando: `#BFAFD0`.

## Footer
- Fondo: `#685666`.
- Titulo, links y copy principal: `#F5F2F8`.
- Links secundarios: blanco con opacidad alta.
- Hover links: bajar opacidad o usar fondo `rgba(255,255,255,0.10)`.

## Componentes reutilizables
### Boton primario
```html
<button class="inline-flex items-center justify-center rounded-pill bg-venus-primary px-6 py-3 font-body font-semibold text-white transition hover:opacity-95">
  Comprar ahora
</button>
```

### Card producto
```html
<article class="rounded-venus border border-venus-border/40 bg-venus-surface p-4 shadow-venus">
  <div class="mb-4 overflow-hidden rounded-2xl bg-venus-soft/40"></div>
  <h3 class="font-display text-2xl text-venus-text">Lip Oil Peony</h3>
  <p class="mt-2 font-body text-sm text-venus-muted">Acabado glow y textura ligera.</p>
</article>
```

### Input
```html
<input class="w-full rounded-pill border border-venus-border bg-white px-4 py-3 font-body text-venus-text placeholder:text-venus-muted focus:outline-none focus:ring-4 focus:ring-venus-primary/20" />
```

## Implementacion recomendada en este proyecto
- Mantener `tema_tokens` para colores base, tipografias, radios y sombras.
- Mantener `tema_componentes` para header, footer, badge, button.primary, button.secondary, input.search, card.product, home.hero, home.surface, home.card, checkout.stepper y confirmation.hero.
- Generar las variables CSS desde PHP en `tienda_helper.php` y `tienda_admin_helper.php`.
- Dejar `venus.css` como capa de reglas visuales que consume `var(--tv-...)` sin quemar colores finales.
- Registrar en SQL los valores por defecto del tema VENUS para que la parametrizacion pueda modificarlos despues sin tocar la vista.
