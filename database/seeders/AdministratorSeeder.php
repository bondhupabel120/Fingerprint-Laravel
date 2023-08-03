<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdministratorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Administrator',
            'email' => 'administrator@gmail.com',
            'password' => bcrypt('administrator@gmail.com'),
            'user_type' => 1,
        ]);
    }
}
