<?php 
class Errors{
	public $res = [];
	public function error($array){

		if($array['type'] == "email" && 
		!filter_var($array['email'], FILTER_VALIDATE_EMAIL))
		{
			$this->res['success'] = false;
			$this->res['msg'] = 'Email inválido';
			echo json_encode($this->res);
			exit;
		}

		if($array['type'] == "senha" && $array['senha'] != $array['confirm'])
		{
			$this->res['success'] = false;
			$this->res['msg'] = 'Senhas diferentes';
			echo json_encode($this->res);
			exit;
		}

		if($array['type'] == "login" && strpos($array['login'], " "))
		{
			$this->res['success'] = false;
			$this->res['msg'] = 'Não são permitidos espaços em branco no nome de login.';
			echo json_encode($this->res);
			exit;
		}

		if($array['type'] == "phone")
		{
			$phone = $array['phone'];
			foreach($array['phone'] as $key => $number){

				if(substr($number,0,4) == "+244"){
					$phone[] = substr($number,4);
				}else if(substr($number,0,3) == "244"){
					$phone[] = substr($number,3);
				}else{
					$phone[] = $number;
				}
			}

			for ($i=0; $i <count(array_filter($phone)) ; $i++) { 

				if(strlen($phone[$i]) <> 9 ){
					$this->res['success'] = false;
					$this->res['msg'] = 'Existem contactos inválidos. Verifique os seus destinatarios.';
					echo json_encode($this->res);
					exit;
				}

				if(strlen($phone[$i]) == 9 && substr($phone[$i], 0,1) == 9 
					&& substr($phone[$i], 1,1) == 2 || 
					substr($phone[$i], 1,1) == 9 || substr($phone[$i], 1,1) == 1 ||
					substr($phone[$i], 1,1) == 3 || substr($phone[$i], 1,1) == 4){
				}else{
					$this->res['success'] = false;
					$this->res['msg'] = 'Existem contactos inválidos. Verifique os seus destinatarios.';
					echo json_encode($this->res);
					exit;
				}
			}
		}
	}
}
?>