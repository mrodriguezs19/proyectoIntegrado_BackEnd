<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductoPedido extends Model
{
    
    protected $table="productopedidos";
    protected $fillable = array('id_producto','id_comanda','cantidad');

    public function comandas()
	{	
		
		return $this->belongsTo('App\Comanda');
    }
    public function productos()
	{	
		
		return $this->belongsTo('App\Producto');
	}
}
