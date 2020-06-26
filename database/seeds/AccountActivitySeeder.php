<?php

use App\User;
use Illuminate\Database\Seeder;

class AccountActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker  = \Faker\Factory::create();

        $users = User::all();

        foreach ($users as $user) {
            factory(App\Models\AccountActivity::class, 5)
                ->make([
                    'user_id' => $user->id,
                ])
                ->each(function ($activity) use ($user) {
                    $user->createActivity($activity->amount, $activity->description);
                });
        }
    }
}
