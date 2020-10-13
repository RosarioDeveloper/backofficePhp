<?php
require_once APP_URL."config/router.php";
require_once MODULES_URL."categorias/categorias.controller.php";

class CategoriaRoutes extends CategoriaController
{ 
   //Constructor
	public function __construct($req){
      $this->req = (object) $req;
      $this->routes = new Router();
      $this->initRoutes();
   }
    
   function initRoutes()
   {
      $this->routes->get("/categorias", function(){  
         return $this->selectAll($this->req); 
      });

      $this->routes->post("/categorias/select", function(){  
         return $this->selectBy($this->req); 
      });

      $this->routes->post("/categorias/create", function(){  
         return $this->create($this->req); 
      });

      $this->routes->put("/categorias/update", function(){  
         return $this->update($this->req); 
      });

      $this->routes->delete("/categorias/delete", function(){  
         return $this->delete($this->req); 
      });
   }
}

//$catRoutes = new CategoriaRoutes();

