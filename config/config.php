<?php
ob_start();
session_start();
//session_cache_expire(10);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf8");
header('Access-Control-Allow-Credentials: true');
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
//header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
header("Access-Control-Allow-Headers: *");

//define("SITE_ORIGIN", $_SERVER['HTTP_REFERER']);
define("APP_API", 'https://backoffice.angolasites.com/');
define("APP_URL", dirname(__DIR__) . '/');
define("SHARED_URL", APP_URL . "src/shared/");
define("MODULES_URL", APP_URL . "src/modules/");

define("URL", isset($_GET['url']) ? $_GET['url'] : "required");
define("UPLOAD_PATH", "C:/Developer/Web/app/tolksms-2/src/assets/uploads/");

// Salts de Criptografia
define("CHARS", "ABCDEFGHIJKLMNOPQRSTUVYXWZ" . "abcdefghijklmnopqrstuvyxwz" . "0123456789" . "!@#$%¨&*()_+=");
define("SALT", 'mrktp-AkAkhhY-wwQzxQ$zx-0Jkw0Jkws"');
define("PASSWORD", "$5$." . CHARS);
define("SECRET_KEY", 'TalKSMS-AkAkhhY-wwQzxQ$zx-0Jkw0Jkw');

//DB conenct
define("HOST", "localhost");
define("DB", "angolasi_bazara");
define("USER", 'root');
define("SENHA", '');

