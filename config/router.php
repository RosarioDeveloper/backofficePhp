<?php
global $routes;


class Router
{
   private $action;
   public $req;

   function __construct()
   {
      $this->response['error'] = "Cannot Get";
      $this->urlServer = $_SERVER['REQUEST_URI'];
      $this->reqMethod = $_SERVER['REQUEST_METHOD'];
      $this->req = json_decode(file_get_contents('php://input'), true);
   }

   function get($action, Closure $callback)
   {
      if($this->reqMethod == "GET" && $action == $this->urlServer){  
         $this->handleRoute($action, $callback);
         exit;
      }  
   }

   function post($action, Closure $callback)
   {
      if($this->reqMethod == "POST" && $action == $this->urlServer){ 
         $this->handleRoute($action, $callback);
         exit;
      }   
   }

   function put($action, Closure $callback)
   {
      if($this->reqMethod == "PUT" && $action == $this->urlServer){  
         $this->handleRoute($action, $callback);
         exit;
      } 
   }

   function delete($action, Closure $callback)
   {
      if($this->reqMethod == "DELETE" && $action == $this->urlServer){  
         $this->handleRoute($action, $callback);
         exit;
      }   
   }

   function handleRoute($action, $callback){
      global $routes;
      $action = trim($action, "/");
      $routes[$action] = $callback;

      $req = trim($this->urlServer, '/');

      if($action == $req){
         $callbackReq = $routes[$req];
         echo call_user_func($callbackReq);
         exit;
      }
      //$this->dispatch();
   }
}
