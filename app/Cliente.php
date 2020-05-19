<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table="clientes";
    protected $fillable = array('id_mesa');
    public $timestamps = false;

    public function comandas()
	{	
		
		return $this->hasMany('App\Comanda');
    }
    public function mesas()
	{	
		
		return $this->belongsTo('App\Mesa');
    }
    public function facturas()
	{	
		
		return $this->hasOne('App\Factura');
	}
}
