<?php
namespace App\Models;
use App\Core\Database;
class Payment {
 public function all(): array { return Database::connection()->query('SELECT py.*,r.code,c.name client_name FROM payments py JOIN reservations r ON r.id=py.reservation_id JOIN clients c ON c.id=r.client_id ORDER BY py.id DESC')->fetchAll(); }
 public function reservations(): array { return Database::connection()->query("SELECT r.id,r.code,c.name client_name,r.total_amount-COALESCE((SELECT SUM(amount) FROM payments p WHERE p.reservation_id=r.id),0) balance FROM reservations r JOIN clients c ON c.id=r.client_id WHERE r.status!='Cancelada' ORDER BY r.id DESC")->fetchAll(); }
 public function save(array $d): void { $db=Database::connection(); $s=$db->prepare('SELECT total_amount-COALESCE((SELECT SUM(amount) FROM payments WHERE reservation_id=?),0) balance FROM reservations WHERE id=?');$s->execute([$d['reservation_id'],$d['reservation_id']]);$row=$s->fetch(); if(!$row || $d['amount']<=0 || $d['amount']>$row['balance']) throw new \RuntimeException('El monto debe ser positivo y no superar el saldo pendiente.'); $s=$db->prepare('INSERT INTO payments(reservation_id,amount,payment_date,method,reference,notes) VALUES(?,?,?,?,?,?)');$s->execute([$d['reservation_id'],$d['amount'],$d['payment_date'],$d['method'],$d['reference'],$d['notes']]); }
}
