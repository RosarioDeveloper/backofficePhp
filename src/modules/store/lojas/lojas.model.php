<?php
require_once APP_URL."database/db.php";
use Illuminate\Database\Eloquent\Model;

class Loja extends Model {
	protected $table = "tb_lojas";
	protected $filllabe = [
		'id',
		'url',
		'info'
	];

	public $timestamps = true;
}
?>