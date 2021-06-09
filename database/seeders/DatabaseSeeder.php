<?php

namespace Database\Seeders;

use App\School;
use App\Teacher;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        $this->truncateTables(['professions', 'user_profiles', 'skill_user', 'skills', 'users', 'teams', 'schools', 'teachers', 'subjects', 'subject_teacher']);

        $this->call(ProfessionSeeder::class);
        $this->call(SkillSeeder::class);
        $this->call(TeamSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(SubjectSeeder::class);
        $this->call(SchoolSeeder::class);
        $this->call(TeacherSeeder::class);


    }

    public function truncateTables(array $tables)
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        foreach ($tables as $table) {
            DB::table($table)->truncate();
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
