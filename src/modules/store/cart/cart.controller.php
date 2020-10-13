<?php
require_once MODULES_URL . "store/cart/cart.model.php";
require_once MODULES_URL . "store/produtos/produtos.controller.php";
require_once MODULES_URL . "store/orders/order.controller.php";

class CartController extends ProdutosController
{
   public $res = [];

	protected function validations($action, $req){
		switch ($action) {
			case "produtos":
            $this->produto = Produtos::find($req->produtoId);
            $this->produto != null ? $this->produto->info = json_decode($this->produto['info']):false;

            if($this->produto == null){
               $this->res['success'] = false;
               $this->res['msg'] = 'Este produto não existe.';
               echo json_encode($this->res);
               exit;
            }
            return $this->produto;
         break;

         case "updateCartItem":
            $this->items = CartItems::where("id", $req->itemId)->first();
            if($this->items == null){
               $this->res['success'] = false;
               $this->res['msg'] = 'Este item não existe.';
               echo json_encode($this->res);
               exit;
            }
            return $this->items;
         break;
		}
   }

   protected function initCart($req){
      $init = new Cart();
      $init->token = str_shuffle(SALT);
      $init->loja_id = isset($req->lojaId) ? $req->lojaId : 0;
      $init->save();

      $this->cart = $init;
   }

   protected function selectItems($req, $headers, $retun){
      $res = [];
      $cartToken = isset($headers->CartToken) ? $headers->CartToken : "";
      $cart = Cart::where("token", $cartToken)->first();

      if($cart != null){
         $items = CartItems::selectRaw('tb_cart_items.*,
         tb_produtos.info as produtoInfo, tb_produtos.url as produtoUrl')
         ->join('tb_produtos', 'tb_cart_items.produto_id', '=', 'tb_produtos.id')
         ->where("cart_id", $cart->id)->get();

         $res['cart'] = $cart;
         $res['totalItems'] = 0;
         $res['totalCart'] = 0;

         foreach($items as $item){
            $item['produtoInfo'] = json_decode($item['produtoInfo'], true);
            $res['items'][] = $item;
            $res['totalItems'] += $item['qtd'];
            $res['totalCart'] += $item['subtotal'];
         }
      }

      if($retun){  return  $res; }{ echo json_encode($res); }
   }

   protected function add($req, $headers){
      $cartToken = isset($headers->CartToken) ? $headers->CartToken : null;
      $cart = Cart::where("token", $cartToken)->where("status", 0)->first();

      if($cart == null){
         $this->initCart($req);
         $this->insertItems($req, $this->cart);
      }

      if($cart != null){
         $this->insertItems($req, $cart);
      }
   }

   protected function insertItems($req, $cart){
      $this->produto = $this->validations("produtos", $req);
      $items = CartItems::where("produto_id", $this->produto['id'])
      ->where('cart_id', $cart->id)->first();

      //Adicona um item ao carrinho
      if($items == null){
         $itemQtd = $this->updateStock('insert', $req, null, $this->produto);
         $subtotal = $req->qtd * $this->produto['info']->price;

         $addItem = new CartItems();
         $addItem->qtd = $this->clear_str($req->qtd);
         $addItem->subtotal = $this->price_format($subtotal);
         $addItem->produto_id = $this->clear_str($this->produto['id']);
         $addItem->cart_id = $this->clear_str($cart->id);
         $addItem->save();

         $addItem['cartToken'] = $cart->token;
         $res['cartToken'] = $cart->token;
         echo json_encode($addItem);
      }

      //Actualiza caso o item já existe no carrinho
      if($items != null){
         $itemQtd = $this->updateStock('insert', $req, $items, $this->produto);
         $subtotal =  $itemQtd * $this->produto['info']->price;
         CartItems::where('id', $items->id)->update([
            "qtd" => $this->clear_str($itemQtd),
            "subtotal" => $this->price_format($subtotal)
         ]);

         $itemUpdated = CartItems::where("id", $items->id)->first();
         echo json_encode($itemUpdated);
      }
   }

   protected function updateItems($req){
      $items = $this->validations("updateCartItem", $req);
      $this->produto = Produtos::find($items->produto_id);
      $this->produto['info'] = json_decode($this->produto['info']);
      $itemQtd = $this->updateStock('update', $req, $items, $this->produto);

      $subtotal =  $itemQtd * $this->produto['info']->price;
      CartItems::where('id', $items->id)->update([
         "qtd" => $this->clear_str($itemQtd),
         "subtotal" => $this->price_format($subtotal)
      ]);

      $this->res['success'] = true;
      $this->res['msg'] = "Item actuazado com sucesso";
      echo json_encode($this->res);
   }

   protected function deleteItem($req){
      $getItem = CartItems::find($req->itemId);

      if($getItem != null){
         $produto = Produtos::find($getItem->produto_id);
         $produto->stock = $produto->stock + $getItem->qtd;
         $produto->save();
         $delete = CartItems::where("id", $req->itemId)->delete();
      }

      $this->res['success'] = $getItem != null ? true: false;
      $this->res['msg'] = $this->res['success'] ?
      'Item eliminado com sucesso': 'Este item não existe no carrinho.';
      echo json_encode($this->res);
   }

   //Checkout
   protected function checkout($req, $headers){
      $this->order = new OrderController();
      $cartInfo = (object) $this->selectItems($req, $headers, true);
      $updateCart = Cart::where('token', $headers->CartToken)->update([
         'total' => $cartInfo->totalCart,
         'status' => 1
      ]);

      //Cria a encomenda
      if($cartInfo->cart->status || $updateCart){
         $req = (array) $req; $req['cartId'] = $cartInfo->cart->id;
         $this->order->createOrder((object) $req, $headers, $cartInfo);
      }
      //echo json_encode($updated->status);
   }
}
