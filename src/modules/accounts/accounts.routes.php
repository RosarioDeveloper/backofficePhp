<?php
require_once APP_URL."config/router.php";
require_once MODULES_URL."accounts/accounts.controller.php";

class AccountRoutes extends AccountController
{ 
   //Constructor
	public function __construct($req){
      $this->req = $req;
      $this->routes = new Router();
      $this->initRoutes();
   }
    
   function initRoutes()
   {
      $this->routes->get("/accounts", function(){  
         return $this->selectAll($this->req); 
      });

      $this->routes->post("/accounts/create", function(){  
         return $this->create($this->req); 
      });

      $this->routes->put("/accounts/update", function(){  
         return $this->updateInfo($this->req); 
      });

      $this->routes->put("/accounts/update-senha", function(){  
         return $this->updateSenha($this->req); 
      });

      $this->routes->delete("/accounts/delete", function(){  
         return $this->delete($this->req); 
      });
   }
}

//$catRoutes = new AccountRoutes();

