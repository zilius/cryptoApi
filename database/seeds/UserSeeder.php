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

        //This seeds with multiple labels with same currencies
//        factory(App\Asset::class, 100000)->create();
    }
}
