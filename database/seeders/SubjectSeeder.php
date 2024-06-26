<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Subject;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Subject::create([
            'id' => '1',
            'name' => 'English',
            'description' => 'asdf',
            'category_id' => '2',
        ]);

        Subject::create([
            'id' => '2',
            'name' => 'c++',
            'description' => '2asdf',
            'category_id' => '3',
        ]);

        Subject::create([
            'id' => '3',
            'name' => 'python',
            'description' => '2ffs',
            'category_id' => '3',
        ]);

        Subject::create([
            'id' => '4',
            'name' => 'maths',
            'description' => '2sdf',
            'category_id' => '1',
        ]);

        Subject::create([
            'id' => '5',
            'name' => 'physics',
            'description' => '2sdf',
            'category_id' => '1',
        ]);
        Subject::create([
            'id' => '6',
            'name' => 'chemistry',
            'description' => '2sdf',
            'category_id' => '1',
        ]);
        Subject::create([
            'id' => '7',
            'name' => 'java',
            'description' => '2sdf',
            'category_id' => '3',
        ]);
        Subject::create([
            'id' => '8',
            'name' => 'Arabic',
            'description' => '2sdf',
            'category_id' => '2',
        ]);
    }
}
