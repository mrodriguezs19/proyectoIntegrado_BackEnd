<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Producto;
use App\Administrador;
class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $administradores=App\Administrador::all()->pluck('id')->toArray();

       // Creamos un bucle para cubrir 5 Administradors:
       for ($i=1; $i<=4; $i++)
       {
           // Cuando llamamos al método create del Modelo Administrador
           // se está creando una nueva fila en la tabla.
           Producto::create(
               [
                    'id'=>$faker->randomNumber($nbDigits = 4, $strict = false), 
                    'nombre'=>$faker->name(),                  
                   'precio'=>$faker->numberBetween($min = 1000, $max = 9000),
                    'tipo'=>$faker->randomElement(['racion','tapa','entrante','postre','bebida']),
                    'especialidad'=>$faker->randomElement(['carne','pescado','vegetariano','alcohol','sinalcohol','salado','dulce']),
                    'id_adm'=> $faker->randomElement($administradores),
               ]
           );
       }
    }
}
