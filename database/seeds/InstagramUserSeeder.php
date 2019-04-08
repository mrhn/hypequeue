<?php

use Illuminate\Database\Seeder;
use App\Models\InstagramUser;
use Faker\Factory;

class InstagramUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        InstagramUser::create([
            'handle' => $faker->firstName,
        ]);
    }
}
