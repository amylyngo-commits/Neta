<?php
namespace App\Controllers;
use App\Core\Controller; use App\Core\Database;
class PublicController extends Controller {
 public function home(): void {
  $packages = Database::connection()->query("SELECT * FROM packages WHERE status='Activo' ORDER BY id DESC LIMIT 3")->fetchAll();
  $this->render('public/home',['packages'=>$packages]);
 }
 public function catalog(): void {
  $db=Database::connection(); $term=trim($_GET['q']??'');
  if($term!==''){ $s=$db->prepare("SELECT * FROM packages WHERE status='Activo' AND (name LIKE ? OR destination LIKE ? OR category LIKE ?) ORDER BY name"); $like='%'.$term.'%'; $s->execute([$like,$like,$like]); $packages=$s->fetchAll(); }
  else $packages=$db->query("SELECT * FROM packages WHERE status='Activo' ORDER BY name")->fetchAll();
  $this->render('public/catalog',['packages'=>$packages,'term'=>$term]);
 }
}
