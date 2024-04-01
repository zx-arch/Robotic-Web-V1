<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'username' => 'AdminIP',
            'email' => 'admin@intanpariwara.com',
            'password' => Hash::make('@dMIN123'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        DB::table('users')->insert([
            'username' => 'PengurusIP',
            'email' => 'pengurus@intanpariwara.com',
            'password' => Hash::make('@IP_pengurus13'),
            'role' => 'pengurus',
            'status' => 'active',
        ]);

        DB::table('users')->insert([
            'username' => 'TestUserIP',
            'email' => 'testinguser@gmail.com',
            'password' => Hash::make('@IP_testing_user'),
            'role' => 'user',
            'status' => 'active',
        ]);

        DB::table('users')->update([
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}