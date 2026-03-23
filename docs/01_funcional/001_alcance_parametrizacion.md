# Alcance de parametrización

## Objetivo
Definir qué elementos de la aplicación serán parametrizables desde el inicio, con el fin de reutilizar la misma lógica de negocio para distintos clientes, variando identidad visual, comportamiento configurable e integraciones.

## Enfoque general
La aplicación corresponderá a una sola tienda base, reutilizable para otros clientes mediante parametrización. No se manejará una estructura multitienda activa en paralelo dentro de la misma implementación inicial.

## Componentes parametrizables
### 1. Identidad visual
Deberá poder parametrizarse como mínimo:

- nombre comercial
- logo principal
- logo alterno
- favicon
- colores principales
- colores secundarios
- colores de apoyo
- tipografías
- estilos de botones
- estilos de tarjetas
- banners
- textos visibles de cabecera y pie de página

### 2. Temas visuales
La aplicación deberá permitir manejar temas para modificar la apariencia general sin alterar la lógica base. Los temas deberán cubrir, como mínimo:

- paleta de colores
- fuentes
- tamaños
- bordes
- espaciados
- estilos por componente
- apariencia de encabezado
- apariencia de pie de página
- apariencia de menús
- apariencia de listados y tarjetas

### 3. Parámetros funcionales
Deberán poder configurarse sin tocar código aspectos como:

- datos generales del negocio
- moneda
- idioma
- textos configurables
- comportamiento de catálogos
- comportamiento del carrito
- comportamiento de checkout
- activación o desactivación de módulos
- configuraciones base de notificaciones
- datos de contacto
- datos de integración

### 4. Integraciones
La estructura deberá permitir registrar parámetros para integraciones futuras, incluyendo:

- servicios externos
- facturación electrónica
- RIPS
- pasarelas de pago
- servicios de mensajería
- correo
- procesos regulatorios

### 5. Contenido administrable
Deberán contemplarse elementos administrables como:

- banners
- textos informativos
- contenido de pie de página
- enlaces externos
- políticas
- términos
- mensajes configurables

## Componentes no parametrizables en etapa inicial
No se parametrizarán en esta etapa:

- reglas internas complejas de negocio no definidas aún
- estructuras transaccionales no documentadas
- flujos funcionales no aprobados
- variaciones de arquitectura entre clientes

## Restricciones
- No se crearán parámetros sin uso definido.
- No se crearán catálogos ambiguos.
- No se utilizará parametrización para reemplazar estructuras relacionales que deban existir de forma formal.
- No se mezclarán parámetros visuales con datos transaccionales.
- No se crearán tablas duplicadas para cubrir variaciones de apariencia.

## Resultado esperado
La parametrización deberá permitir reutilizar la aplicación en otros clientes cambiando configuración, identidad visual, integraciones y módulos habilitados, sin alterar la lógica central ni romper la estructura base.

## Estado del alcance
El alcance aquí descrito corresponde únicamente a la definición base de parametrización. La definición detallada de tablas y relaciones se documentará posteriormente.
EOF
