<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(2)->create();
        $this->call([
            RoleSeeder::class,
            StoreSeeder::class,
            UserSeeder::class,
            ProgressJobSeeder::class,
            ProductSeeder::class,
            SaleSeeder::class,
        ]);
    }
}
