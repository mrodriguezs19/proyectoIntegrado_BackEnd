<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/administradores', function (Request $request) {
    return $request->user();  
});
Route::resource('administradores','AdministradorController');  
Route::resource('empleados','EmpleadoController');
Route::resource('mesas','MesaController');  
Route::resource('productos','ProductoController');
Route::resource('clientes','ClienteController');
Route::resource('facturas','FacturaController');
Route::resource('comandas','ComandaController');
Route::resource('productopedidos','ProductoPedidoController');
Route::resource('users','UserController');










    