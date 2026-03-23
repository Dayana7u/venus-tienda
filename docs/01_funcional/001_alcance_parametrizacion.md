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
El alcance aquí descrito corresponde a la definición base de parametrización y a su primera capa operativa consultable desde navegador luego del login inicial.

## Relación con el acceso inicial
La validación del módulo ahora podrá realizarse mediante el flujo base de acceso:

- ingreso por `backend/index.php`
- autenticación con usuario activo de `public.usuarios`
- redirección a `parametrizacion.php`
- cierre de sesión desde la misma vista


## Cierre actual del bloque administrativo

Se dejó parametrización como módulo CRUD base para la administración inicial del proyecto.

### Secciones habilitadas

- Temas
- Branding
- Parámetros
- Módulos
- Integraciones
- Menús
- Roles
- Usuarios

### Alcance del diseño inicial

El diseño actual aplica únicamente para login y parametrización, como base administrativa inicial.


## Ajuste actual del bloque administrativo

- El login de parametrización queda separado del futuro login de la tienda.
- Login y parametrización se podrán seguir ajustando visualmente desde el mismo bloque administrativo.
- La vista administrativa ya incluye comportamiento responsive y confirmaciones visuales para el CRUD.
- Antes de pasar a la tienda, el frente inmediato es terminar de pulir usuarios, alertas y novedades técnicas del código.


## Ajuste visual posterior

Se ajustó la vista administrativa para reducir títulos y tarjetas en escalas altas de pantalla, mantener mejor proporción en 150% de escala y retirar bloques informativos innecesarios del acceso administrativo.


13. Ajustes visuales y operativos del CRUD administrativo
Se evidencia que, el panel lateral del formulario fue corregido para mantener la distribución de campos y acciones sin dejar el contenido en blanco al ejecutar la acción Limpiar.
Se evidencia que, se agregó la acción de eliminar dentro de la tabla y dentro del panel del formulario, manteniendo borrado lógico y trazabilidad de usuario y fecha de borrado.
Se evidencia que, los registros de creación, edición y borrado fueron ajustados para reforzar el llenado de columnas de auditoría y la persistencia correcta de valores binarios en PostgreSQL.
Se evidencia que, se compactó nuevamente la distribución visual del panel lateral y del encabezado para disminuir espacios muertos durante la validación en escritorio con escala alta.


Se ajustó la distribución visual del panel lateral de parametrización para reducir espacios en blanco, retirar la acción eliminar del formulario y homogeneizar la altura visual de tarjetas y secciones CRUD.

Se ajustó nuevamente la distribución visual del módulo para reducir espacios vacíos del encabezado, sidebar, cards de resumen y panel lateral, manteniendo seguridad fuera del flujo visual hasta definir su alcance real.
