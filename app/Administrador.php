<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Administrador extends Model
{
   // Nombre de la tabla en MySQL.
	protected $table="administradores";
   

	protected $fillable = array('usuario','nombre_completo','correo','contrasena');
	// Eliminamos el campo remember_token
	protected $hidden = ['contrasena'];
  

	public function empleados()
	{	
		
		return $this->hasMany('App\Empleado');
	}
	public function mesas()
	{	
		
		return $this->hasMany('App\Mesa');
	}
	public function productos()
	{	
		
		return $this->hasMany('App\Producto');
	}
	public function users()
	{	
		
		return $this->hasOne('App\User');
	}
}
