<?php
require_once MODULES_URL . "store/lojas/lojas.model.php";

class LojasController extends Scripts
{
	public $res = [];

	protected function validations($action, $req)
	{
		switch ($action) {
			case "insert":
				$url = $this->clear_str(strtolower($req->info['nome']));
				$isExist = Loja::where('url', $url)->get();

				if (count($isExist) > 0) {
					$this->res['success'] = false;
					$this->res['msg'] = 'Já existe uma loja com este nome.';
					echo json_encode($this->res);
					exit;
				}
			break;
		}
	}

	protected function select($req){
		$lojas = [];
		$res = Loja::orderBy('id', 'desc')->get();
		foreach ($res as $prod) {
			$prod->info = json_decode($prod->info);
			$lojas[] = $prod;
		}
		echo json_encode($lojas);
	}

	protected function selectBy($req)
	{
		if($req->type == "id"){
			$select = Loja::where('id', $req->id)->get();
			$select != null ? $select->info = json_decode($select->info): false;
			echo json_encode($select);
		}

		if($req->type == "url"){
			$select = Loja::where("url", $req->url)->first();
			$select != null ? $select->info = json_decode($select->info): false;
			echo json_encode($select);
		}
		
	}

	protected function create($req){
		try {
			$this->validations("insert", $req);

			$insert = new Loja;
			$insert->url = $this->clear_str($req->info['nome']);
			$insert->info = json_encode($req->info);
			$insert->save();

			$this->res['success'] =  true;
			$this->res['msg'] = 'Loja criada com sucesso';
			echo json_encode($this->res);

		} catch (\Throwable $th) {
			$this->res['success'] = false;
			$this->res['msg'] = 'Ocorreu um erro ao criar loja.';
			echo json_encode($this->res);
		}
	}

	protected function update($req){
		try {
			$update = Loja::where('id', $req->id)->update([
				'url' => $this->clear_str(strtolower($req->info['nome'])),
				'info' => json_encode($req->info)
			]);

			$this->res['success'] = $update ? true : false;
			$this->res['msg'] = $this->res['success'] ? 'Loja actualizado com sucesso' :
			'Este loja não existe.';

		} catch (\Throwable $th) {
			$this->res['success'] = false;
			$this->res['msg'] = 'Ocorreu um erro ao actualizar loja.';
		}
		echo json_encode($this->res);
	}


	protected function delete($req){
		try {
			$delete = Loja::where('id', $req->id)->delete();
			$this->res['success'] = $delete ? true : false;
			$this->res['msg'] = "Loja eliminado com sucesso";
		} catch (\Throwable $th) {
			$this->res['success'] = false;
			$this->res['msg'] = 'Ocorreu um erro ao eliminar loja.';
		}
		echo json_encode($this->res);
	}
}
