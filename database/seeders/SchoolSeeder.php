<?php

namespace Database\Seeders;

use App\School;
use App\Subject;
use App\Teacher;
use Illuminate\Database\Seeder;

class SchoolSeeder extends Seeder
{
    private $subjects;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->subjects = Subject::all();

        foreach (range(1, 20) as $i) {
            $this->createRandomSchool();
        }
    }

    public function createRandomSchool()
    {
        $school = School::factory()->create([]);

        foreach (range(1, 20) as $i) {

            $teacher = Teacher::factory()->create([
                'school_id' => $school->id,
            ]);
            $teacher->subjects()->attach($this->subjects->random(rand(0, 5)));
        }


    }
}
