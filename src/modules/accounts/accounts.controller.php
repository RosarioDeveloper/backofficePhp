<?php
require_once MODULES_URL."accounts/accounts.model.php";

class AccountController extends Scripts
{
	public $res = [];

	protected function validation($type, $req){
		if(isset($req['email'])){
			$this->error(array("type"=>"email", "email"=>$req['email']));
		}

		if($type == "create"){
			$isExist = Account::where('email', $req['email'])->first();
			if($isExist != null){
				$this->res['success'] = false;
				$this->res['msg'] = 'Este email já está a ser utilizado';
				echo json_encode($this->res);
				exit;
			}
		}
	}
	
	protected function selectAll($req){
		try {
			$contas = [];
			$res = Account::orderBy('id', 'desc')->get();
			foreach($res as $cat){ 
				$cat['info'] = json_decode($cat['info']);
				unset($cat['senha']);
				$contas[] = $cat;
			}
			echo json_encode($contas);

		} catch (\Throwable $th) {
			$this->res['success'] = false;
			$this->res['msg'] = 'Ocorreu um erro ao selecionar conta.';
			echo json_encode($this->res);
		}
	}

	protected function create($req){
		try {
			$this->validation("create", $req);

			$insert = new Account;
			$insert->email = $req['email'];
			$insert->telefone = isset($req['telefone']) ? $req['telefone'] : "";
			$insert->senha = crypt($req['password'], PASSWORD);
			$insert->info = json_encode($req['info']);
			$insert->type = "pessoal";
			$insert->status = "active";
			$insert->save();

			unset($insert['senha'], $insert['id']);
			$insert['info'] = json_decode($insert['info'], true);
			$insert['token'] = $this->generateToken(array(
            "id" => $insert['id'], "email" => $insert['login']
         ));
			echo json_encode($insert);	

		} catch (\Throwable $th) {
			$this->res['success'] = false;
			$this->res['msg'] = 'Ocorreu um erro ao criar conta.';
			echo json_encode($this->res);
		}
	}

	protected function updateInfo($req){
		try {
			$this->validation("update", $req);

			$update = Account::where('id', $req['id'])->update([
				'email' => $req['email'],
				'telefone' => $req['telefone'],
				'info' => json_encode($req['info']),
			]);
			$this->res['success'] = $update ? true : false;
			$this->res['msg'] = $this->res['success'] ? 
			'Dados actualizados com sucesso' : 'Esta conta não existe.';

		}catch (\Throwable $th) {
			$this->res['success'] = false;
			$this->res['msg'] = 'Ocorreu um erro ao actualizar conta.';
		}
		echo json_encode($this->res);
	}

	protected function updateSenha($req){
		try {
			$senha = Account::find($req['id']);

			if($senha->senha != crypt($req['oldPassword'], PASSWORD)){
				$this->res['success'] = false;
				$this->res['msg'] = 'Senha Desconhecida';
				echo json_encode($this->res);
				exit;
			}
			
			if($req['passwordConfirm'] != $req['newPassword']){
				$this->res['success'] = false;
				$this->res['msg'] = 'Senhas Diferentes';
				echo json_encode($this->res);
				exit;
			}

			$update = Account::where('id', $req['id'])->update([
				'senha' => crypt($req['newPassword'], PASSWORD)
			]);

			$this->res['success'] = $update ? true : false;
			$this->res['msg'] = $this->res['success'] ? 
			'Senha actualizada com sucesso' : 'Esta conta não existe.';
			echo json_encode($this->res);

		} catch (\Throwable $th) {
			$this->res['success'] = false;
			$this->res['msg'] = 'Ocorreu um erro ao actualizar conta.';
			echo json_encode($this->res);
		}
	}

	protected function delete($req){
		try {
			$delete = Account::where('id', $req['id'])->delete();
			$this->res['success'] = $delete ? true : false;
			$this->res['msg'] = $this->res['success'] ? 
			'Conta eliminada com sucesso' : 'Não foi possível eliminar a sua conta.';

		} catch (\Throwable $th) {
			$this->res['success'] = false;
			$this->res['msg'] = 'Ocorreu um erro ao eliminar conta.';
		}
		echo json_encode($this->res);
	}
}
