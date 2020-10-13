<?php
require_once APP_URL."config/router.php";
require_once MODULES_URL."store/cart/cart.controller.php";

class CartRoutes extends CartController
{
   //Constructor
	public function __construct($req){
      $this->req = (object) $req;
      $this->headers = (object) getallheaders();

      $this->routes = new Router();
      $this->initRoutes();
   }

   function initRoutes()
   {

      $this->routes->post("/cart/add", function(){
         return $this->add($this->req, $this->headers);
      });

      $this->routes->get("/cart/items", function(){
         return $this->selectItems($this->req, $this->headers, false);
      });

      $this->routes->put("/cart/items/update", function(){
         return $this->updateItems($this->req, $this->headers);
      });

      $this->routes->post("/cart/items/delete", function(){
         return $this->deleteItem($this->req);
      });

      $this->routes->post("/cart/checkout", function(){
         return $this->checkout($this->req, $this->headers);
      });
   }
}

//$catRoutes = new CartRoutes();

