<?php
require_once APP_URL."config/router.php";
require_once MODULES_URL."store/orders/order.controller.php";

class OrderRoutes extends OrderController
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
      $this->routes->get("/orders", function(){
         return $this->select($this->req, $this->headers);
      });
   }
}

//$catRoutes = new OrderRoutes();

