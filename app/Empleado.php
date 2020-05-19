<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    // Nombre de la tabla en MySQL.
	protected $table="empleados";

    
	protected $fillable = array('dni','nombre_completo','correo','contrasena','sueldo','puesto','id_adm');
	
	public function administradores()
	{	
		
		return $this->belongsTo('App\Administrador');
	}
	public function comandas()
	{	
		
		return $this->hasMany('App\Comanda');
	}
}
