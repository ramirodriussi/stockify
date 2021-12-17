<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::insert([
            [
                'name' => 'Administrador',
                'email' => 'admin@example.com',
                'password' => \Hash::make('password'),
                'role_id' => 1,
                'access' => 1,
                'created_at' => \Carbon\Carbon::now(),
            ],
            [
                'name' => 'Empleado',
                'email' => 'empleado@example.com',
                'password' => \Hash::make('password'),
                'role_id' => 2,
                'access' => 1,
                'created_at' => \Carbon\Carbon::now(),
            ],
        ]);
    }
}
