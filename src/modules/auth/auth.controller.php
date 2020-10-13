<?php
require_once MODULES_URL."accounts/accounts.model.php";

class AuthController extends Scripts
{
	public $res = [];
	
	protected function login($req){
		try {
         $auth = Account::where('email', $req['login'])
         ->where('senha', crypt($req['password'], PASSWORD))->get();

         if(count($auth) == 0){
            $this->res['success'] = false;
			   $this->res['msg'] = 'Email ou senha desconhecido.';
            echo json_encode($this->res);
            exit;
         }

         $auth = $auth[0];
         unset($auth['senha']);
         $auth['info'] = json_decode($auth['info']);
         
         $token = $this->generateToken(array(
            "id" => $auth['id'],
            "email" => $auth['login']
         ));

         $auth['token'] = $token;
         echo json_encode($auth);

			//echo json_encode(array("token" => $token), $auth);

		} catch (\Throwable $th) {
			$this->res['success'] = false;
			$this->res['msg'] = 'Ocorreu ao autenticar-se.';
			echo json_encode($this->res);
		}
	}
}
