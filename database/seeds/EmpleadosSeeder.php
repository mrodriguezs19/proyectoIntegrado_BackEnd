<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Administrador;
use App\Empleado;

class EmpleadosSeeder extends Seeder
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
       $administradores=App\Administrador::all()->pluck('id')->toArray();
       $arrayValues = ['camarero','cocinero'];


       // Creamos un bucle para cubrir 5 Administradors:
       for ($i=1; $i<=10; $i++)
       {

           // Cuando llamamos al método create del Modelo Administrador
           // se está creando una nueva fila en la tabla.
           Empleado::create(
               [
                'id'=>$faker->unique()->randomNumber($nbDigits = 4, $strict = false),
                   'dni'=>$faker->unique()->word(),
                   'nombre_completo'=>$faker->name(),
                   'correo'=>$faker->email(),
                   'puesto'=>$arrayValues[rand(0,1)],
                   'contrasena'=>$faker->creditCardNumber(),
                   'sueldo'=>$faker->numberBetween($min = 1000, $max = 9000),
                    'id_adm'=> $faker->randomElement($administradores),               
               ]
           );
       }
    }
}
