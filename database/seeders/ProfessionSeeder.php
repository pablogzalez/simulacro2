<?php

namespace Database\Seeders;

use App\Profession;
use Illuminate\Database\Seeder;

class ProfessionSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        Profession::create([
            'title' => 'Desarrollador Back-End',
        ]);

        Profession::create([
            'title' => 'Desarrollador Front-End',
        ]);

        Profession::create([
            'title' => 'Diseñador web',
        ]);

        Profession::factory()->times(17)->create();
    }
}
