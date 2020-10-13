<?php
require_once MODULES_URL."store/produtos/produtos.routes.php";
require_once MODULES_URL."store/cart/cart.routes.php";
require_once MODULES_URL."store/lojas/lojas.routes.php";
require_once MODULES_URL."store/orders/order.routes.php";

class StoreRoutes{

   function __construct($req){
      new ProdutosRoutes($req);
      new CartRoutes($req);
      new LojasRoutes($req);
      new OrderRoutes($req);
   }
}
