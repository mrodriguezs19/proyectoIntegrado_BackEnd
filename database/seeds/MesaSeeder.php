<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Administrador;
use App\Mesa;

class MesaSeeder extends Seeder
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
        

        for ($i=1; $i<=10; $i++){

        Mesa::create(
            [
                'id'=>$faker->unique()->randomNumber($nbDigits = 4, $strict = false),
                'sillas'=>$faker->randomNumber($nbDigits = 2, $strict = false),
                'estado'=>$faker->randomElement(['vacio']),
                'id_adm'=> $faker->randomElement($administradores), 
            ] 
            );
        }  
    }
}
