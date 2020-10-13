<?php
require_once APP_URL."database/db.php";
use Illuminate\Database\Eloquent\Model;

class Cart extends Model {
	protected $table = "tb_cart";
	protected $filllabe = [
		'id',
		'info',
		'total',
		'status',
		'delivery',
		'token',
		'loja_id',
		'client_id'
	];
	public $timestamps = false;
}


class CartItems extends Model {
	protected $table = "tb_cart_items";
	protected $filllabe = [
		'id',
		'qtd',
		'subtotal',
		'produto_id',
		'cart_id',
	];
	public $timestamps = false;
}

?>