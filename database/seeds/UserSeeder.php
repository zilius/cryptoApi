<?php
declare(strict_types=1);

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
        factory(App\User::class, 10)->create();

        //Debug user
        $user = new \App\User();
        $user->name = 'debug';
        $user->email = 'debug@debug.com';
        $user->email_verified_at = now();
        $user->password = 'debug';
        $user->api_token = 'debug';
        $user->remember_token = 'debug';
        $user->save();

        //This seeds with multiple labels with same currencies
//        factory(App\Asset::class, 100000)->create();
    }
}
