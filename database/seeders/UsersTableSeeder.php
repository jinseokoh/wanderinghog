<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory(20)->create();

        User::all()->each(function (User $user) {
            $profile = Profile::factory()->make();
            $user->profile()->update($profile->toArray());
        });
    }
}
