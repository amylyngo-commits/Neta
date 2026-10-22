# Integrar el portal de clientes sin perder la base de datos existente

## Importante

Si ya importaste `database/neta_sgi.sql` de la versión administrativa, **NO vuelvas a importar ese archivo**. Ese script contiene instrucciones `DROP TABLE` y puede borrar las tablas y los datos actuales.

El portal de clientes usa la misma base `neta_sgi`; no necesita una segunda base de datos ni otro archivo SQL de creación. El esquema inicial ya incluye:

- `users.role` con el valor `Cliente`.
- `users.status` para activar o desactivar cuentas.
- `clients` con correo único para relacionar el perfil del viajero.
- `packages` con estado `Activo`/`Inactivo`.
- `reservations` para las solicitudes hechas por clientes y las registradas por el administrador.
- `payments` para que la administración registre los pagos de las reservas.

## Integración recomendada

1. Haz una copia de seguridad de la carpeta `C:\xampp\htdocs\NETA_SGI`.
2. Haz una copia de seguridad de la base `neta_sgi` desde phpMyAdmin: selecciona la base, pulsa **Exportar** y guarda el SQL.
3. Descomprime el paquete de integración en `C:\xampp\htdocs\` y acepta combinar carpetas/reemplazar solo los archivos de código indicados en el paquete. No borres la carpeta del proyecto.
4. Conserva `config/config.php` y verifica que apunte a tu base existente: host `127.0.0.1`, base `neta_sgi`, usuario `root` y la contraseña que tengas configurada.
5. No ejecutes `database/neta_sgi.sql` otra vez.
6. Inicia Apache y MySQL en XAMPP y abre `http://localhost/NETA_SGI/index.php?page=home`.

## Cómo queda conectado

- El administrador crea/activa paquetes en el panel administrativo.
- El catálogo público consulta esos mismos paquetes de `packages` cuyo estado sea `Activo`.
- El viajero se registra en `users` con rol `Cliente` y se crea o actualiza su ficha en `clients`.
- Cuando el viajero solicita una reserva, se inserta una fila en `reservations` con estado `Pendiente`.
- El administrador revisa y confirma esa misma reserva desde su panel.
- Los pagos se siguen registrando desde el panel administrativo en `payments`.

## Prueba de integración

1. Entra al administrador y comprueba que puedes iniciar sesión con tu cuenta existente.
2. Crea un paquete o asegúrate de que haya uno con estado `Activo`.
3. Abre el catálogo público y comprueba que aparece el paquete.
4. Registra una cuenta de cliente con un correo que no esté usado en `users`.
5. Inicia sesión como cliente y solicita una reserva para una fecha futura.
6. Cierra sesión e inicia sesión como administrador. La reserva debería aparecer en el módulo de reservas con estado `Pendiente`.

Si algo falla, revisa el mensaje de error y el registro de PHP/Apache antes de modificar el esquema. No elimines ni recrees tablas para resolver errores de conexión.
