<?php
require_once MODULES_URL . "store/produtos/produtos.model.php";
require_once MODULES_URL . "categorias/categorias.model.php";
require_once MODULES_URL . "store/lojas/lojas.model.php";

class ProdutosController extends Scripts
{
	public $res = [];

	protected function validations($action, $req)
	{
		switch ($action) {
			case "insert":
				$url = $this->clear_str(strtolower($req['info']['nome']));
				$isExist = Produtos::where('url', $url)
					->where('account_id', 1)->get();
				if (count($isExist) > 0) {
					$this->res['success'] = false;
					$this->res['msg'] = 'Este Produto já existe.';
					echo json_encode($this->res);
					exit;
				}
				break;
		}
	}

	protected function selectAll($req){
		$produtos = [];
		$res = Produtos::orderBy('id', 'desc')->get();
		foreach ($res as $prod) {
			$prod['info'] = json_decode($prod['info']);
			$produtos[] = $prod;
		}
		echo json_encode($produtos);
	}

	protected function productsByUrl($req){
		$produtos = [];
		$select = Produtos::selectRaw('tb_produtos.*, tb_categorias.info as categoria')
		->join('tb_categorias', 'tb_produtos.categoria_id', '=', 'tb_categorias.id')
		->where("tb_produtos.url", $req['url'])->get();
		foreach($select as $res) {
			$res['info'] = json_decode($res['info']);
			$cat = json_decode($res['categoria'], true);
			$res['categoria'] = array(
				"url" => $this->clear_str($cat['nome']),
				"info"=> $cat
			);
			$produtos[] = $res;
		}
		echo json_encode($produtos);
	}

	protected function relatedProducts($req){
		$produtos = [];
		$select = Produtos::where('categoria_id', $req['categoriaId'])
		->where("url", '<>', $req['url'])
		->get();

		foreach($select as $res) {
			$res['info'] = json_decode($res['info']);
			$produtos[] = $res;
		}
		echo json_encode($produtos);
	}

	protected function productsByCategoria($req){
		$produtos = [];
		$select = Produtos::selectRaw('tb_produtos.*')
		->join('tb_categorias', 'tb_produtos.categoria_id', '=', 'tb_categorias.id')
		->where('tb_categorias.url', $this->clear_str($req['categoriaUrl']))
		->get();

		foreach($select as $res) {
			$res['info'] = json_decode($res['info']);
			$produtos[] = $res;
		}
		echo json_encode($produtos);
	}

	protected function productsByLoja($req){
		$produtos = [];
		$select = Produtos::selectRaw('tb_produtos.*')
		->join('tb_lojas', 'tb_produtos.loja_id', '=', 'tb_lojas.id')
		->where("tb_lojas.url", $req['lojaUrl'])->get();

		foreach($select as $res) {
			$res['info'] = json_decode($res['info']);

			if($req['categoriaId'] == null){$produtos[] = $res;}
			if($req['categoriaId'] != null && $res['categoria_id'] == $req['categoriaId']){
				$produtos[] = $res;
			}
		}
		echo json_encode($produtos);
	}

	protected function selectBy($req)
	{
		$produtos = [];
		if($req['type'] == "url"){ $this->productsByUrl($req); }
		if($req['type'] == "related"){ $this->relatedProducts($req); }
		if($req['type'] == "categoriaUrl"){$this->productsByCategoria($req); }
		if($req['type'] == "lojaUrl"){ $this->productsByLoja($req); }
	}

	protected function create($req)
	{
		try {
			$this->validations("insert", $req);

			$insert = new Produtos;
			$insert->ref = isset($req['ref']) ? $req['ref'] : str_shuffle("dqwdqwf586");
			$insert->url = $this->clear_str(strtolower($req['info']['nome']));
			$insert->info = json_encode($req['info']);
			$insert->stock = $req['stock'];
			$insert->type = $req['type'];
			$insert->categoria_id = $req['categoriaId'];
			$insert->loja_id = isset($req['lojaId']) ? $req['lojaId'] : 0;
			$insert->account_id = 1;

			$this->res['success'] = $insert->save() ? true : false;
			$this->res['msg'] = $this->res['success'] ? 'Produto criado com sucesso'
				: 'Erro ao criar produto';
			echo json_encode($this->res);
		} catch (\Throwable $th) {
			$this->res['success'] = false;
			$this->res['msg'] = 'Ocorreu um erro ao criar produto.';
			echo json_encode($this->res);
		}
	}

	protected function update($req)
	{
		$update = Produtos::where('id', $req['id'])->update([
			'url' => $this->clear_str(strtolower($req['info']['nome'])),
			'info' => json_encode($req['info']),
			'stock' => $req['stock'],
			'type' => $req['type'],
			'categoria_id' => $req['categoriaId'],
			'loja_id' => isset($req['lojaId']) ? $req['lojaId'] : 0
		]);

		$this->res['success'] = $update ? true : false;
		$this->res['msg'] = $this->res['success'] ?
		'Produto actualizado com sucesso' : 'Este produto não existe.';
		echo json_encode($this->res);
	}

	public function updateStock($type, $req, $items, $produto){

		switch($type){
			case "update":
				if($req->qtd > $items->qtd){
					$this->itemQtd =  $items->qtd + ($req->qtd - $items->qtd);
					//$this->itemQtd =  $req->qtd;
					$this->stock = $produto['stock'] - ($req->qtd - $items->qtd);
					$this->invalid = $req->qtd - $items->qtd > $produto['stock'];
				}

				if($req->qtd == $items->qtd){
					$this->itemQtd =  $req->qtd;
					$this->stock = $produto['stock'];
					$this->invalid = false;
				}

				if($req->qtd < $items->qtd){
					//$this->itemQtd = $items->qtd - $req->qtd;
					$this->itemQtd = $req->qtd;
					$this->stock = $produto['stock'] + $items->qtd - $req->qtd;
					$this->invalid = false;
				}
			break;

			default:
				$this->itemQtd =  $items == null ? $req->qtd : $items->qtd + $req->qtd;
				$this->stock = $produto['stock'] - $req->qtd;
				$this->invalid = $produto['stock'] <= 0 ? true : $req->qtd > $produto['stock'];
			break;
		}

		if($this->invalid){
			$this->res['success'] = false;
			$this->res['msg'] = $this->stock <= 0 ? 'Produto fora do stock' :'Stock insuficiente no máximo ('.$produto['stock'].')';
         echo json_encode($this->res);
         exit;
		}
		//Produtos::where("id", $produto['id'])->update(["stock" =>$this->stock]);;
		return $this->itemQtd;
	}

	protected function delete($req)
	{
		try {
			$delete = Produtos::where('id', $req['id'])->delete();
			$this->res['success'] = $delete ? true : false;
			$this->res['msg'] = "Produto eliminado com sucesso";
		} catch (\Throwable $th) {
			$this->res['success'] = false;
			$this->res['msg'] = 'Ocorreu um erro ao eliminar produto.';
		}
		echo json_encode($this->res);
	}
}
