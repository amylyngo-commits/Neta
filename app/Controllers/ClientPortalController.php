<?php
namespace App\Controllers;
use App\Core\Controller; use App\Core\Database;
class ClientPortalController extends Controller {
 private function clientId(): int { $s=Database::connection()->prepare('SELECT id FROM clients WHERE email=? LIMIT 1'); $s->execute([$_SESSION['user']['email']]); return (int)$s->fetchColumn(); }
 public function index(): void {
  $db=Database::connection(); $clientId=$this->clientId();
  $s=$db->prepare('SELECT COUNT(*) FROM reservations WHERE client_id=?');$s->execute([$clientId]);$total=(int)$s->fetchColumn();
  $s=$db->prepare("SELECT COUNT(*) FROM reservations WHERE client_id=? AND status IN ('Pendiente','Confirmada')");$s->execute([$clientId]);$active=(int)$s->fetchColumn();
  $s=$db->prepare('SELECT r.*,p.name package_name,p.destination,p.currency,COALESCE((SELECT SUM(amount) FROM payments py WHERE py.reservation_id=r.id),0) paid FROM reservations r JOIN packages p ON p.id=r.package_id WHERE r.client_id=? ORDER BY r.created_at DESC LIMIT 5');$s->execute([$clientId]);
  $packages=$db->query("SELECT * FROM packages WHERE status='Activo' ORDER BY id DESC LIMIT 3")->fetchAll();
  $this->render('client/area',['total'=>$total,'active'=>$active,'reservations'=>$s->fetchAll(),'packages'=>$packages]);
 }
 public function reservations(): void {
  $s=Database::connection()->prepare('SELECT r.*,p.name package_name,p.destination,p.currency,COALESCE((SELECT SUM(amount) FROM payments py WHERE py.reservation_id=r.id),0) paid FROM reservations r JOIN packages p ON p.id=r.package_id WHERE r.client_id=? ORDER BY r.created_at DESC');$s->execute([$this->clientId()]);
  $this->render('client/reservations',['reservations'=>$s->fetchAll()]);
 }
 public function book(): void {
  if($_SERVER['REQUEST_METHOD']!=='POST') \redirect('catalog'); \csrf_check(); $packageId=(int)($_POST['package_id']??0); $date=$_POST['travel_date']??''; $passengers=max(1,min(20,(int)($_POST['passengers']??1)));
  try { $db=Database::connection(); $s=$db->prepare("SELECT * FROM packages WHERE id=? AND status='Activo'");$s->execute([$packageId]);$pkg=$s->fetch();
   if(!$pkg || !$date || $date<date('Y-m-d')) throw new \RuntimeException('Selecciona un paquete disponible y una fecha válida.');
   if((int)$pkg['stock']>0 && $passengers>(int)$pkg['stock']) throw new \RuntimeException('No hay suficientes plazas disponibles para esa cantidad de pasajeros.');
   $clientId=$this->clientId(); if(!$clientId) throw new \RuntimeException('No encontramos tu perfil de cliente. Contacta con la agencia.');
   $code='NET-'.date('ymd').'-'.strtoupper(bin2hex(random_bytes(2))); $total=(float)$pkg['price']*$passengers;
   $s=$db->prepare("INSERT INTO reservations(code,client_id,package_id,travel_date,passengers,total_amount,status,notes) VALUES(?,?,?,?,?,?,'Pendiente',?)");$s->execute([$code,$clientId,$packageId,$date,$passengers,$total,'Solicitud realizada desde el portal web']);
   \flash('success','¡Solicitud enviada! Tu código de reserva es '.$code.'. La agencia confirmará la disponibilidad.'); \redirect('client-reservations');
  } catch(\RuntimeException $e) { \flash('error',$e->getMessage()); \redirect('catalog'); } catch(\Throwable $e) { \flash('error','No se pudo enviar la solicitud. Inténtalo nuevamente.'); \redirect('catalog'); }
 }
}
