<?php
require_once APP_URL."config/config.php";
require_once APP_URL."src/shared/scripts.php";
require_once APP_URL."src/routes.php";

class App {
   private $req;

   function __construct(){
      $this->req = json_decode(file_get_contents('php://input'), true);
      $this->routes();
   }

   function routes(){
      new AuthRoutes($this->req);
      new CategoriaRoutes($this->req);
      new AccountRoutes($this->req);
      new StoreRoutes($this->req);
   }
}