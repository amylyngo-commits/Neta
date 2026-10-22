<?php
require __DIR__.'/../config/bootstrap.php';
$hash=password_hash('NetaDemo2026!', PASSWORD_DEFAULT);
$s=App\Core\Database::connection()->prepare('UPDATE users SET password_hash=? WHERE email=?');
$s->execute([$hash,'admin@neta.bo']);
echo "Contraseña demo restablecida.\n";
