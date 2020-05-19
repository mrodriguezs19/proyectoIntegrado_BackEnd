<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Cliente;
use App\Mesa;

class ClienteSeeder extends Seeder
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
       $mesas=App\Mesa::all()->pluck('id')->toArray();
       


       // Creamos un bucle para cubrir 5 Administradors:
       for ($i=1; $i<=10; $i++)
       {

           // Cuando llamamos al método create del Modelo Administrador
           // se está creando una nueva fila en la tabla.
           Cliente::create(
               [
                    'id'=>$faker->unique()->randomNumber($nbDigits = 4, $strict = false),
                    'id_mesa'=>$faker->randomElement($mesas),
                   
                                   
               ]
           );
       }
    }
}