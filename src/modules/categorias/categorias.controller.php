<?php
require_once MODULES_URL . "categorias/categorias.model.php";

class CategoriaController extends Scripts{
	
	public $res = [];

	protected function selectAll($req){
		$categorias = [];
		$res = Categoria::all();

		foreach ($res as $cat) {
			$cat['info'] = json_decode($cat['info']);
			$categorias[] = $cat;
		}

		echo json_encode($res);
	}

	protected function selectBy($req)
	{
		if($req->type == "url"){
			$select = Categoria::where("url", $req->url)->first();
			$select != null ? $select->info = json_decode($select->info): false;
			echo json_encode($select);
		}

		if($req->type == "id"){
			$select = Categoria::where("id", $req->url)->first();
			$select != null ? $select->info = json_decode($select->info): false;
			echo json_encode($select);
		}
	}

	protected function create($req){
		try {
			$isExist = Categoria::where('url', $this->clear_str($req->info['nome']))
			->get();
			if (count($isExist) > 0) {
				$this->res['status'] = false;
				$this->res['msg'] = 'Ja existe uma categoria com este nome';
				echo json_encode($this->res);
				exit;
			}

			$insert = new Categoria;
			$insert->url = $this->clear_str($req->info['nome']);
			$insert->info = json_encode($req->info);
			$insert->save();

			$insert->info = json_decode($insert->info);
			echo json_encode($insert);

		} catch (\Throwable $th) {
			$this->res['status'] = false;
			$this->res['msg'] = 'Ocorreu um erro ao criar categoria.';
			echo json_encode($this->res);
		}
	}

	protected function update($req)
	{
		try {
			$update = Categoria::where('id', $req->id)->update([
				'url' => $this->clear_str($req->info['nome']),
				'info' => json_encode($req->info)
			]);

			$this->res['status'] = $update ? true : false;
			$this->res['msg'] = $this->res['status'] ?
			'Categoria Actualiza com sucesso' : 'Esta categoria não existe.';

		} catch (\Throwable $th) {
			$this->res['status'] = false;
			$this->res['msg'] = 'Ocorreu um erro ao actualizar categoria.';
		}
		echo json_encode($this->res);
	}

	protected function delete($req)
	{
		try {
			$delete = Categoria::where('id', $req->id)->delete();
			$this->res['status'] = $delete ? true : false;
			$this->res['msg'] = $this->res['status'] ?
				'Categoria eliminada com sucesso' : 'Não foi feita nenhuma actualização.';
		} catch (\Throwable $th) {
			$this->res['status'] = false;
			$this->res['msg'] = 'Ocorreu um erro ao eliminar categoria.';
		}

		echo json_encode($this->res);
	}
}
