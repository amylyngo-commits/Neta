<?php
namespace App\Models;
use App\Core\Database;
class Reservation {
 public function all(): array { return Database::connection()->query('SELECT r.*, c.name client_name, p.name package_name, COALESCE((SELECT SUM(amount) FROM payments py WHERE py.reservation_id=r.id),0) paid FROM reservations r JOIN clients c ON c.id=r.client_id JOIN packages p ON p.id=r.package_id ORDER BY r.id DESC')->fetchAll(); }
 public function save(array $d): void { $s=Database::connection()->prepare('INSERT INTO reservations(code,client_id,package_id,travel_date,passengers,total_amount,status,notes) VALUES(?,?,?,?,?,?,?,?)');$s->execute([$d['code'],$d['client_id'],$d['package_id'],$d['travel_date'],$d['passengers'],$d['total_amount'],$d['status'],$d['notes']]); }
 public function clients(): array { return Database::connection()->query('SELECT id,name FROM clients ORDER BY name')->fetchAll(); }
 public function packages(): array { return Database::connection()->query("SELECT id,name,price,currency,stock FROM packages WHERE status='Activo' ORDER BY name")->fetchAll(); }
}
