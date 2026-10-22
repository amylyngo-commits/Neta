<?php
namespace App\Controllers;
use App\Core\Controller; use App\Core\Database;
class UserController extends Controller { public function index(): void {$this->requireAdmin();$users=Database::connection()->query('SELECT id,name,email,role,status,created_at FROM users ORDER BY id')->fetchAll();$this->render('users/index',compact('users'));} }
