<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(DataSeeder::class) and;

        DB::table('users')->insert([
            [
                'name'              => 'Abdul Alim',
                'email'             => 'aa@gmail.com',
                'password'          => Hash::make('12345678'),
                'status'            => 1,
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'name'              => 'M. Kiron',
                'email'             => 'kiron@gmail.com',
                'password'          => Hash::make('12345678'),
                'status'            => 1,
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'name'              => 'Tasnif Khan',
                'email'             => 'dip@gmail.com',
                'password'          => Hash::make('12345678'),
                'status'            => 1,
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
        ]);
    }
}
