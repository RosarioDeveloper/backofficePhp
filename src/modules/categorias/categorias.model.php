<?php
require_once APP_URL . "database/db.php";

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
	protected $table = "tb_categorias";
	protected $filllabe = [
		'id',
		'url',
		'info'
	];

	public $timestamps = true;

	public function produtos(){
		return $this->hasMany('Produtos', 'categoria_id');
	}
}
