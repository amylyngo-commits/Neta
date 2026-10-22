<?php
namespace App\Models;
use App\Core\Database;
class Package {
 public function all(): array { return Database::connection()->query('SELECT * FROM packages ORDER BY id DESC')->fetchAll(); }
 public function active(): array { return Database::connection()->query("SELECT * FROM packages WHERE status='Activo' ORDER BY name")->fetchAll(); }
 public function save(array $d): void { $db=Database::connection(); if(!empty($d['id'])){$s=$db->prepare('UPDATE packages SET name=?,destination=?,category=?,duration_days=?,price=?,currency=?,stock=?,description=?,status=? WHERE id=?');$s->execute([$d['name'],$d['destination'],$d['category'],$d['duration_days'],$d['price'],$d['currency'],$d['stock'],$d['description'],$d['status'],$d['id']]);}else{$s=$db->prepare('INSERT INTO packages(name,destination,category,duration_days,price,currency,stock,description,status) VALUES(?,?,?,?,?,?,?,?,?)');$s->execute([$d['name'],$d['destination'],$d['category'],$d['duration_days'],$d['price'],$d['currency'],$d['stock'],$d['description'],$d['status']]);} }
}
