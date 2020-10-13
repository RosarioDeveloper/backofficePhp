<?php 
require_once APP_URL."vendor/autoload.php";
use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;
$capsule->addConnection([
   "driver"    => "mysql",
   "host"      => HOST,
   "database"  => DB,
   "username"  => USER,
   "password"  => SENHA,
   'charset' => 'utf8mb4',
   'collation' => 'utf8mb4_unicode_ci',
]);
 
$capsule->setAsGlobal();
$capsule->bootEloquent();