<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Progress_job;

class ProgressJobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Progress_job::insert([
            ['progress' => 0],
        ]);
    }
}
