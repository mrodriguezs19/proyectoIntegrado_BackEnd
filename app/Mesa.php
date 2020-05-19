<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mesa extends Model
{
    protected $table="mesas";
    protected $fillable = array('sillas','estado','id_adm');
    public $timestamps = false;

    public function clientes()
	{	
		
		return $this->hasOne('App\Cliente');
    }
    public function administradores()
	{	
		
		return $this->belongsTo('App\Administrador');
    }
    
	
}
