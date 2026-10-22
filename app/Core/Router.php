<?php
namespace App\Core;
class Router {
    public function dispatch(): void {
        $page = $_GET['page'] ?? (isset($_SESSION['user']) ? (($_SESSION['user']['role'] ?? '') === 'Cliente' ? 'client-area' : 'dashboard') : 'home');
        if ($page === 'logout') { $_SESSION = []; session_destroy(); header('Location: index.php?page=home'); exit; }
        $public = ['home','catalog','login','client-login','client-register'];
        if (!in_array($page, $public, true) && empty($_SESSION['user'])) { header('Location: index.php?page=client-login'); exit; }
        $clientPages = ['client-area','client-booking','client-reservations'];
        $adminPages = ['dashboard','clients','client-save','client-delete','packages','package-save','reservations','reservation-save','payments','payment-save','reports','users'];
        if (!empty($_SESSION['user'])) {
            $role = $_SESSION['user']['role'] ?? '';
            if (in_array($page, $clientPages, true) && $role !== 'Cliente') { header('Location: index.php?page=dashboard'); exit; }
            if (in_array($page, $adminPages, true) && $role === 'Cliente') { header('Location: index.php?page=client-area'); exit; }
        }
        $routes = [
          'home'=>['App\\Controllers\\PublicController','home'],
          'catalog'=>['App\\Controllers\\PublicController','catalog'],
          'login'=>['App\\Controllers\\AuthController','login'],
          'client-login'=>['App\\Controllers\\ClientAuthController','login'],
          'client-register'=>['App\\Controllers\\ClientAuthController','register'],
          'client-area'=>['App\\Controllers\\ClientPortalController','index'],
          'client-booking'=>['App\\Controllers\\ClientPortalController','book'],
          'client-reservations'=>['App\\Controllers\\ClientPortalController','reservations'],
          'dashboard'=>['App\\Controllers\\DashboardController','index'],
          'clients'=>['App\\Controllers\\ClientController','index'],
          'client-save'=>['App\\Controllers\\ClientController','save'],
          'client-delete'=>['App\\Controllers\\ClientController','delete'],
          'packages'=>['App\\Controllers\\PackageController','index'],
          'package-save'=>['App\\Controllers\\PackageController','save'],
          'reservations'=>['App\\Controllers\\ReservationController','index'],
          'reservation-save'=>['App\\Controllers\\ReservationController','save'],
          'payments'=>['App\\Controllers\\PaymentController','index'],
          'payment-save'=>['App\\Controllers\\PaymentController','save'],
          'reports'=>['App\\Controllers\\ReportController','index'],
          'users'=>['App\\Controllers\\UserController','index'],
        ];
        if (!isset($routes[$page])) { http_response_code(404); echo 'Página no encontrada'; return; }
        [$class,$method] = $routes[$page]; (new $class())->$method();
    }
}
