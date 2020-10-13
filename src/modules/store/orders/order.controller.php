<?php
require_once MODULES_URL . "store/orders/order.model.php";
require_once MODULES_URL . "store/cart/cart.controller.php";

class OrderController extends Scripts
{
   public $res = [];

	protected function validations($action, $req){
		switch ($action) {
			case "create":
            $find = Order::find($req->cartId);
            if($find != null){
               $this->res['success'] = true;
               $this->res['msg']= 'Encomenda efectuada com sucesso.';
               echo json_encode($this->res);
               exit;
            }
         break;
		}
   }

   protected function select($req, $headers){
      $select = Order::selectRaw('tb_orders.*, tb_cart.total')
      ->join('tb_cart', 'tb_orders.cart_id', '=', 'tb_cart.id')
      ->where('tb_cart.token', '=', $headers->CartToken)->first();
      $select->info = json_decode($select->info);
      echo json_encode($select);
   }

   public function createOrder($req, $headers, $cart){
      $find = Order::where('cart_id', $req->cartId)->first();
      if($find == null){
         $create = new Order();
         $create->info = json_encode($req);
         $create->cart_id = $req->cartId;
         $create->save();

         /*$urlMailText = APP_API."src/modules/store/orders/mail/";
         $urlMailText2 = MODULES_URL."store/orders/mail/";

         $httpQuery = http_build_query(array('req'=> $req, 'cart'=> $cart));
         $contextOpts = array("http" => array(
            'method'  => 'POST',
            'header'  => 'Content-Type: application/x-www-form-urlencoded',
            'content' => $httpQuery
         ));
         $context = stream_context_create($contextOpts);
         $admMsg = file_get_contents($urlMailText."mail-client.php", false,  $context);*/

         //Email for admin
         $admMsg = 'O cliente '.$req->delivery['nome'].' efectuou uma encomenda no valor de:
         <b>'.number_format($cart->totalCart,2,',','.').' Kz</b>';
         $this->email = [];
         $this->email['from'] = $req->delivery['email'];
         $this->email['remitente'] = "Bazara Store";
         $this->email['email'] = array('rosariodeveloper@hotmail.com');
         $this->email['assunto'] = "Solicitação de Encomenda";
         $this->email['mensagem'] = nl2br($admMsg);
         $this->send_email($this->email);

         //Email for client
         if($this->msg_mail){
            $clientMsg = 'Olá Sr(ª) '.$req->delivery['nome'].'.<br>
            Obrigado pelo seu pedido. No momento estamos aguardar a confirmação do
            pagamento.';

            $this->email = [];
            $this->email['from'] = 'rosariodeveloper@hotmail.com';
            $this->email['remitente'] = "Bazara Store";
            $this->email['email'] = array($req->delivery['email']);
            $this->email['assunto'] = "Confirmação da Encomenda";
            $this->email['mensagem'] = nl2br($clientMsg);

            set_time_limit(0);
            $this->send_email($this->email);
         }
      }

      $this->res['success'] = true;
      $this->res['msg']= 'Encomenda efectuada com sucesso.';
      echo json_encode($this->res);
   }
}
