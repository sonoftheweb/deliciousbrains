<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker  = \Faker\Factory::create();

        factory(App\User::class, 2)->create()->each(function ($user) use ($faker) {

            $user->profile()->save(factory(App\Models\UserProfile::class)->make());
        });
    }
}
