<?php
require_once APP_URL."database/db.php";
use Illuminate\Database\Eloquent\Model;

class Order extends Model {
	protected $table = "tb_orders";
	protected $filllabe = [
      'id',
      'data',
		'info',
		'status',
		'cart_id'
	];
	public $timestamps = false;
}
?>
