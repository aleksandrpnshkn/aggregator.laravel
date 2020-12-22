<?php

use App\DrivingSchool;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        factory(DrivingSchool::class, 20)->create();
    }
}
