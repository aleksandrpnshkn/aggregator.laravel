<?php

use App\DrivingCategory;
use Illuminate\Database\Seeder;

class DrivingCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(DrivingCategory::class, 10)->create();
    }
}
