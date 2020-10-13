<?php
require_once APP_URL."database/db.php";
use Illuminate\Database\Eloquent\Model;

class Client extends Model {
	protected $table = "tb_cart";
	protected $filllabe = [
		'id',
		'email',
		'senha',
		'info',
		'type',
	];
	public $timestamps = false;
}
?>