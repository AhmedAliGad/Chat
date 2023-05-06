<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create(['name' => 'user1', 'phone' => '01020304050', 'password' => bcrypt('password1')]);
        User::create(['name' => 'user2', 'phone' => '01030405060', 'password' => bcrypt('password2')]);
    }
}
