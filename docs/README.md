# NETA — Sistema de Gestión Integral (versión inicial)

Prototipo académico desarrollado según el perfil de proyecto de NETA: PHP 8, MySQL/MariaDB, HTML5, CSS3, JavaScript y arquitectura MVC.

## Módulos incluidos
- Inicio de sesión y roles iniciales (Administrador / Agente de Ventas).
- Dashboard con indicadores y reservas recientes.
- Clientes: alta, búsqueda y eliminación (solo administrador; con protección por integridad referencial).
- Paquetes turísticos: alta y consulta del catálogo.
- Reservas: registro con código automático y seguimiento de estado.
- Pagos: registro de pagos parciales y validación de saldo.
- Reportes operativos básicos: pagos por mes y paquetes con más reservas.
- Consulta de usuarios y roles (solo administrador).

## Requisitos
- PHP 8.0 o superior con extensiones PDO y pdo_mysql.
- MySQL 8 / MariaDB.
- Apache (XAMPP recomendado para la presentación local).

## Instalación con XAMPP
1. Copia la carpeta `NETA_SGI` dentro de `C:\xampp\htdocs\`.
2. Inicia Apache y MySQL desde XAMPP.
3. Abre `http://localhost/phpmyadmin` y ejecuta el archivo `database/neta_sgi.sql`.
4. Abre `config/config.php` y verifica host, nombre de BD, usuario y contraseña de MySQL.
5. Accede a `http://localhost/NETA_SGI/`.

## Acceso demo
- Correo: `admin@neta.bo`
- Contraseña: `NetaDemo2026!`

Si el acceso no funciona después de importar SQL, ejecuta una vez `php scripts/reset_demo_password.php` desde la carpeta del proyecto (con PHP en PATH). También puedes generar un hash nuevo con `php -r "echo password_hash('NetaDemo2026!', PASSWORD_DEFAULT), PHP_EOL;"` y actualizar el campo `password_hash` del usuario administrador.

## Ejecución alternativa
Desde la carpeta del proyecto: `php -S 127.0.0.1:8000`. Luego abre `http://127.0.0.1:8000`.

## Estructura
- `app/Core`: conexión PDO, router, controlador base y helpers.
- `app/Controllers`: lógica de navegación y procesos.
- `app/Models`: consultas y persistencia.
- `app/views`: vistas por módulo y layouts compartidos.
- `config`: configuración del sistema.
- `database`: esquema y datos de demostración.
- `public/assets`: estilos y JavaScript.

## Alcance y limitaciones de esta entrega
Es una primera versión de demostración. No incluye una pasarela de pago real, emisión de facturas del SIN, conexión GDS, envío de correos, gestión completa de proveedores, edición avanzada de registros, migración histórica ni recomendaciones inteligentes entrenadas. Los importes de reservas/pagos se registran en Bs para control interno; la conversión real de monedas y tasas configurables queda para una siguiente iteración. Cambia las credenciales y configura HTTPS antes de cualquier despliegue público.


## Portal público y clientes (versión inicial)
- `index.php?page=home`: página de presentación pública.
- `index.php?page=catalog`: catálogo de paquetes activos y formulario de solicitud.
- `index.php?page=client-register`: registro de cuenta de viajero.
- `index.php?page=client-login`: acceso de clientes.
- `index.php?page=client-area`: panel privado del viajero.
- `index.php?page=client-reservations`: historial de solicitudes.

El cliente puede registrarse, consultar paquetes y enviar una solicitud de reserva. Las solicitudes quedan en estado `Pendiente`; la agencia debe confirmar disponibilidad desde el panel administrativo. Esta versión no procesa pagos en línea ni envía confirmaciones por correo. Para probar el registro, importa el SQL en una base limpia y abre `index.php?page=home`.
