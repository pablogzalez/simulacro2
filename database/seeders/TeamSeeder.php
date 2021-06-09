<?php

namespace Database\Seeders;

use App\Team;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        Team::factory()->create(['name' => 'IES Ingeniero']);

        Team::factory()->times(99)->create();
    }
}
