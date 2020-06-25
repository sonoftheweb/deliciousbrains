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

            $openingBalance = $faker->randomFloat(2, -1000, 5000); // in dollars

            $openingBalanceDescription = 'Opening Balance';

            $user->createActivity($openingBalance, $openingBalanceDescription, \Carbon\Carbon::now()->subDays($faker->numberBetween(5, 10)));
        });
    }
}
