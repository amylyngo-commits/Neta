<?php
namespace App\Controllers;
use App\Core\Controller; use App\Core\Database;
class ReportController extends Controller { public function index(): void {$db=Database::connection();$monthly=$db->query("SELECT DATE_FORMAT(payment_date,'%Y-%m') period, SUM(amount) total FROM payments GROUP BY DATE_FORMAT(payment_date,'%Y-%m') ORDER BY period DESC LIMIT 6")->fetchAll();$popular=$db->query('SELECT p.name,COUNT(r.id) bookings FROM packages p LEFT JOIN reservations r ON r.package_id=p.id GROUP BY p.id ORDER BY bookings DESC,p.name LIMIT 5')->fetchAll();$this->render('reports/index',compact('monthly','popular'));} }
