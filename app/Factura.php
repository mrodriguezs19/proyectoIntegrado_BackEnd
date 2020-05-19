<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    protected $table="facturas";
    protected $fillable = array('id_cliente','cuenta','pagado');

    public function clientes()
	{	
		
		return $this->belongsTo('App\Cliente');
    }
    public function comandas()
	{	
		
		return $this->hasMany('App\Comanda');
    }
}
