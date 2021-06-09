<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Skill;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        Skill::factory()->create(['name' => 'HTML']);
        Skill::factory()->create(['name' => 'CSS']);
        Skill::factory()->create(['name' => 'JS']);
        Skill::factory()->create(['name' => 'PHP']);
        Skill::factory()->create(['name' => 'SQL']);
        Skill::factory()->create(['name' => 'POO']);
        Skill::factory()->create(['name' => 'TDD']);
    }
}
