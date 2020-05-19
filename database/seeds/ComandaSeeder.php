<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Comanda;

use App\Empleado;
use App\Mesa;
use App\Cliente;
use App\Factura;

class ComandaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       // Creamos una instancia de Faker
       $faker = Faker::create();
       $clientes=App\Cliente::all()->pluck('id')->toArray();
       $facturas=App\Factura::all()->pluck('id')->toArray();
       $empleados=App\Empleado::all()->pluck('id')->toArray();     


       // Creamos un bucle para cubrir 5 Administradors:
       for ($i=1; $i<=10; $i++)
       {

           // Cuando llamamos al método create del Modelo Administrador
           // se está creando una nueva fila en la tabla.
           Comanda::create(
               [
                    'id'=>$faker->unique()->randomNumber($nbDigits = 4, $strict = false),
                    'id_cliente'=>$faker->randomElement($clientes),  
                    'id_factura'=>$faker->randomElement($facturas), 
                    'id_empleado'=>$faker->randomElement($empleados),                
                                   
               ]
           );
       }
    }
}
