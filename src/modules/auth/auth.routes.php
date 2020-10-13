<?php
require_once APP_URL."config/router.php";
//require_once APP_URL."src/middlewares/auth.middleware.php";
require_once MODULES_URL."auth/auth.controller.php";

class AuthRoutes extends AuthController
{ 
   //Constructors
	public function __construct($req){
      $this->req = $req;
      $this->routes = new Router();
      $this->initRoutes();
   }
    
   function initRoutes()
   {
      $this->routes->post("/auth", function(){  
         return $this->login($this->req); 
      });
   }
}

//$catRoutes = new AuthRoutes();

