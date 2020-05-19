<?php

use Illuminate\Database\Seeder;

use Faker\Factory as Faker;
use App\Administrador;
class AdministradoresSeeder extends Seeder
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

       // Creamos un bucle para cubrir 5 Administradors:
       for ($i=1; $i<=2; $i++)
       {
           // Cuando llamamos al método create del Modelo Administrador
           // se está creando una nueva fila en la tabla.
           Administrador::create(
               [
                    'id'=>$faker->randomNumber($nbDigits = 4, $strict = false),
                   'usuario'=>$faker->unique()->name(),
                   'nombre_completo'=>$faker->name(),
                   'correo'=>$faker->email(),
                   'contrasena'=>$faker->creditCardNumber()

               ]
           );
       }
    }
}
