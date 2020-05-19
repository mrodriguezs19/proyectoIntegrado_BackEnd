<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table="productos";
    protected $fillable = array('nombre','precio','tipo','especialidad','id_adm');
    public $timestamps = false;

    public function productopedidos()
	{	
		return $this->hasMany('App\ProductoPedido');
    }
    public function administradores()
	{	
		return $this->belongsTo('App\Administrador');
    }
}