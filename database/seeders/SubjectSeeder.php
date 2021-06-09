<?php

namespace Database\Seeders;

use App\School;
use App\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (range(1, 5) as $i) {
            Subject::factory()->create([]);
        }
    }

}
