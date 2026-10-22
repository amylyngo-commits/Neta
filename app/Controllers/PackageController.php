<?php
namespace App\Controllers;
use App\Core\Controller; use App\Models\Package;
class PackageController extends Controller { public function index(): void {$this->render('packages/index',['packages'=>(new Package())->all()]);} public function save(): void {\csrf_check();$d=[];foreach(['id','name','destination','category','duration_days','price','currency','stock','description','status'] as $k)$d[$k]=$_POST[$k]??''; if($d['name']===''||$d['destination']===''||(float)$d['price']<0){\flash('error','Completa los campos obligatorios con valores válidos.');\redirect('packages');}try{(new Package())->save($d);\flash('success','Paquete guardado correctamente.');}catch(\Throwable $e){\flash('error','No se pudo guardar el paquete.');}\redirect('packages');} }
