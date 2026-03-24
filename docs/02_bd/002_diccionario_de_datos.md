# Diccionario de datos resumido

## `public.usuarios`
Tabla de usuarios administrativos del panel.

### Campos relevantes
- `usuario_id`: identificador principal.
- `login`: usuario de acceso.
- `correo`: correo del usuario.
- `clave`: hash de la clave administrativa.
- `sw_superusuario`: control de superusuario.
- `ultimo_ingreso`: último acceso válido.
- `fecha_ultimo_cierre_sesion`: último cierre de sesión registrado.
- `estado` / `borrado`: estado lógico del registro.

## `public.usuarios_sesiones`
Tabla de control de sesiones administrativas.

### Campos relevantes
- `usuario_sesion_id`: identificador principal de la sesión.
- `usuario_id`: usuario relacionado.
- `token`: token único de la sesión.
- `fecha_inicio`: fecha de apertura.
- `fecha_expiracion`: fecha límite de validez.
- `ip`: origen de la sesión.
- `user_agent`: navegador o cliente.
- `estado` / `borrado`: control lógico de la sesión.
- `fecha_modificacion`: último cambio registrado sobre la sesión.
