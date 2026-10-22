<?php
namespace App\Core;
class Controller {
 protected function render(string $view, array $data=[]): void { \view($view,$data); }
 protected function requireAdmin(): void { if(($_SESSION['user']['role'] ?? '')!=='Administrador'){ \flash('error','Esta acción requiere permisos de administrador.'); \redirect('dashboard'); } }
}
