<?php
namespace App\Controllers;
use App\Core\Controller; use App\Core\Database;
class ClientAuthController extends Controller {
 public function login(): void {
  if(!empty($_SESSION['user'])) \redirect(($_SESSION['user']['role']??'')==='Cliente'?'client-area':'dashboard');
  $error='';
  if($_SERVER['REQUEST_METHOD']==='POST') { \csrf_check(); $email=strtolower(trim($_POST['email']??'')); $pass=$_POST['password']??'';
   try { $s=Database::connection()->prepare("SELECT id,name,email,password_hash,role FROM users WHERE email=? AND status=1 LIMIT 1"); $s->execute([$email]); $u=$s->fetch();
    if($u && password_verify($pass,$u['password_hash'])) { session_regenerate_id(true); $_SESSION['user']=['id'=>$u['id'],'name'=>$u['name'],'email'=>$u['email'],'role'=>$u['role']]; \redirect($u['role']==='Cliente'?'client-area':'dashboard'); }
    $error='Correo o contraseña incorrectos. Si aún no tienes cuenta, regístrate como cliente.';
   } catch(\PDOException $e) { $error='No se pudo conectar con la base de datos. Revisa la configuración.'; }
  }
  $this->render('public/client-login',['error'=>$error]);
 }
 public function register(): void {
  if(!empty($_SESSION['user'])) \redirect('client-area'); $error='';
  if($_SERVER['REQUEST_METHOD']==='POST') { \csrf_check(); $name=trim($_POST['name']??''); $email=strtolower(trim($_POST['email']??'')); $phone=trim($_POST['phone']??''); $password=$_POST['password']??''; $confirm=$_POST['confirm_password']??'';
   if($name==='' || !filter_var($email,FILTER_VALIDATE_EMAIL) || strlen($password)<8 || $password!==$confirm) $error='Completa los datos, usa una contraseña de al menos 8 caracteres y confirma correctamente.';
   else { try { $db=Database::connection(); $db->beginTransaction();
     $check=$db->prepare('SELECT id FROM users WHERE email=?'); $check->execute([$email]); if($check->fetch()) throw new \RuntimeException('Ese correo ya está registrado. Inicia sesión o usa otro correo.');
     $check=$db->prepare('SELECT id FROM clients WHERE email=?'); $check->execute([$email]); $client=$check->fetch();
     if($client) { $clientId=(int)$client['id']; $up=$db->prepare('UPDATE clients SET name=?,phone=? WHERE id=?'); $up->execute([$name,$phone,$clientId]); }
     else { $ins=$db->prepare("INSERT INTO clients(name,email,phone,country,preferences) VALUES(?,?,?,'Bolivia','')"); $ins->execute([$name,$email,$phone]); $clientId=(int)$db->lastInsertId(); }
     $ins=$db->prepare("INSERT INTO users(name,email,password_hash,role,status) VALUES(?,?,?,'Cliente',1)"); $ins->execute([$name,$email,password_hash($password,PASSWORD_DEFAULT)]); $userId=(int)$db->lastInsertId(); $db->commit();
     session_regenerate_id(true); $_SESSION['user']=['id'=>$userId,'name'=>$name,'email'=>$email,'role'=>'Cliente']; \redirect('client-area');
    } catch(\RuntimeException $e) { if(isset($db)&&$db->inTransaction())$db->rollBack(); $error=$e->getMessage(); } catch(\Throwable $e) { if(isset($db)&&$db->inTransaction())$db->rollBack(); $error='No se pudo crear la cuenta. Verifica que la base de datos esté actualizada y que el correo no esté repetido.'; }
   }
  }
  $this->render('public/client-register',['error'=>$error]);
 }
}
