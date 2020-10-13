<?php
require_once APP_URL."config/router.php";
require_once MODULES_URL."store/produtos/produtos.controller.php";

class ProdutosRoutes extends ProdutosController
{ 
   //Constructor
	public function __construct($req){
      $this->req = $req;
      $this->routes = new Router();
      $this->initRoutes();
   }
    
   function initRoutes()
   {
      $this->routes->get("/produtos", function(){  
         return $this->selectAll($this->req); 
      });

      $this->routes->post("/produtos/select", function(){  
         return $this->selectBy($this->req); 
      });

      $this->routes->post("/produtos/create", function(){  
         return $this->create($this->req); 
      });

      $this->routes->put("/produtos/update", function(){  
         return $this->update($this->req); 
      });

      $this->routes->delete("/produtos/delete", function(){  
         return $this->delete($this->req); 
      });
   }
}

//$catRoutes = new ProdutosRoutes();

