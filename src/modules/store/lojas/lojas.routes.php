<?php
require_once APP_URL."config/router.php";
require_once MODULES_URL."store/lojas/lojas.controller.php";

class LojasRoutes extends LojasController
{ 
   //Constructor
	public function __construct($req){
      $this->req = (object) $req;
      $this->routes = new Router();
      $this->initRoutes();
   }
    
   function initRoutes()
   {
      $this->routes->get("/lojas", function(){  
         return $this->select($this->req); 
      });

      $this->routes->post("/lojas/select", function(){  
         return $this->selectBy($this->req); 
      });

      $this->routes->post("/lojas/create", function(){  
         return $this->create($this->req); 
      });

      $this->routes->put("/lojas/update", function(){  
         return $this->update($this->req); 
      });

      $this->routes->delete("/lojas/delete", function(){  
         return $this->delete($this->req); 
      });
   }
}

//$catRoutes = new LojasRoutes();

