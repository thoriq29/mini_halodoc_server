<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\AppSeeder;
use Database\Seeders\ContentSeeder;
use Database\Seeders\DoctorSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AppSeeder::class);
        $this->call(ContentSeeder::class);
        $this->call(DoctorSeeder::class);
    }
}
