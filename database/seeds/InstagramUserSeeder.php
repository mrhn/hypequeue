<?php

use Illuminate\Database\Seeder;
use App\Models\InstagramUser;

class InstagramUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(InstagramUser::class, 1)->create();
    }
}
