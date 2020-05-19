<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Cliente;
use App\Factura;

class FacturaSeeder extends Seeder
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
       $arrayValues = ['si','no'];


       // Creamos un bucle para cubrir 5 Administradors:
       for ($i=1; $i<=10; $i++)
       {

           // Cuando llamamos al método create del Modelo Administrador
           // se está creando una nueva fila en la tabla.
           Factura::create(
               [
                    'id'=>$faker->unique()->randomNumber($nbDigits = 4, $strict = false),
                   'id_cliente'=>$faker->randomElement($clientes),
                   'cuenta'=>$faker->randomNumber($nbDigits = 4, $strict = false),
                   'pagado'=>$faker->randomElement($arrayValues),
                                   
               ]
           );
       }
    }
}