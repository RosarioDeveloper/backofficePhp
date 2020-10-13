<?php
require_once APP_URL."database/db.php";
use Illuminate\Database\Eloquent\Model;

class Produtos extends Model {
	protected $table = "tb_produtos";
	protected $filllabe = [
		'id',
		'ref',
		'url',
		'info',
		'stock',
		'type',
		'categoria_id',
		'loja_id',
		'account_id'
	];

	public function categorias(){
		return $this->hasM('Categoria');
	}

	public $timestamps = true;
}
?>