<?php
function e($value): string { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
function redirect(string $page): never { header('Location: index.php?page='.$page); exit; }
function flash(string $type, string $message): void { $_SESSION['flash']=['type'=>$type,'message'=>$message]; }
function csrf_token(): string { if(empty($_SESSION['csrf'])) $_SESSION['csrf']=bin2hex(random_bytes(32)); return $_SESSION['csrf']; }
function csrf_check(): void { if($_SERVER['REQUEST_METHOD']==='POST' && !hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) { http_response_code(419); exit('Solicitud inválida. Recarga la página e inténtalo nuevamente.'); } }
function money($value): string { return 'Bs '.number_format((float)$value, 2, ',', '.'); }
function view(string $name, array $data=[]): void { extract($data); require __DIR__.'/../views/'.$name.'.php'; }
