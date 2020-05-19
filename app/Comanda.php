<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comanda extends Model
{
    // Nombre de la tabla en MySQL.
	protected $table="comandas";
   

	protected $fillable = array('id_empleado','id_cliente','id_factura');
	
	
	public function empleados()
	{	
		
		return $this->belongsTo('App\Empleado');
    }
    public function clientes()
	{	
		
		return $this->belongsTo('App\Cliente');
    }
    public function productopedidos()
	{	
		
		return $this->hasMany('App\ProductoPedido');
    }
    public function facturas()
	{	
		
		return $this->belongsTo('App\Factura');
	}
}
