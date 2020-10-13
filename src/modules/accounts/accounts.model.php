<?php
require_once APP_URL."database/db.php";
use Illuminate\Database\Eloquent\Model;

class Account extends Model {
	protected $table = "tb_accounts";
	protected $filllabe = [
		'id',
		'email',
		'telefone',
		'senha',
		'info',
		'type',
		'status'
	];

	public $timestamps = true;
}
?>