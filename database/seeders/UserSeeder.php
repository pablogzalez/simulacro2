<?php

namespace Database\Seeders;

use App\User;
use App\Profession;
use App\Skill;
use App\Team;
use App\Login;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    private $professions;
    private $skills;
    private $teams;

    public function run()
    {
        $this->fetchRelations();

        $this->createAdmin();

        foreach (range(1, 20) as $i) {
            $this->createRandomUser();
        }
    }

    public function fetchRelations()
    {
        $this->professions = Profession::all();
        $this->skills = Skill::all();
        $this->teams = Team::all();
    }

    public function createAdmin()
    {
        $admin = User::create([
            'team_id' => $this->teams->firstWhere('name', 'IES Ingeniero')->id,
            'first_name' => 'Pepe',
            'last_name' => 'Pérez',
            'email' => 'pepe@mail.es',
            'password' => bcrypt('123456'),
            'role' => 'admin',
            'created_at' => now(),
            'active' => true,
        ]);

        $admin->skills()->attach($this->skills);

        $admin->profile()->create([
            'bio' => 'Programador',
            'profession_id' => $this->professions->where('title', 'Desarrollador Back-End')->first()->id,
        ]);
    }

    public function createRandomUser()
    {
        $user = User::factory()->create([
            'team_id' => rand(0, 2) ? null : $this->teams->random()->id,
            'active' => rand(0, 4) ? true : false,
            'created_at' => now()->subDays(rand(1, 90)),
        ]);

        $user->skills()->attach($this->skills->random(rand(0, 7)));

        $user->profile()->update([
            'profession_id' => rand(0, 2) ? $this->professions->random()->id : null,
        ]);

        Login::factory()->times(rand(1, 10))->create([
            'user_id' => $user->id,
        ]);
    }
}
