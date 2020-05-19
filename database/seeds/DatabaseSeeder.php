<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Administrador;

// Hace uso del modelo de Avion.

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call('AdministradoresSeeder');
        $this->call('EmpleadosSeeder');
        $this->call('ProductoSeeder');
        $this->call('MesaSeeder');
        $this->call('ClienteSeeder');
        $this->call('FacturaSeeder');
        $this->call('ComandaSeeder');


    }
}
