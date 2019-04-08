<?php

use Illuminate\Database\Seeder;
use App\Models\InstagramUser;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(InstagramUserSeeder::class);
    }
}
