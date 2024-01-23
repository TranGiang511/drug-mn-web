<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        DB::table('users')->insert([
            'name' => 'GiangTran',
            'email' => 'giang@gmail.com',
            'phone' => '0348588952',
            'password' => Hash::make('Giang@511'),
            'role' => 'admin',
            'created_at' => now(),
        ]);

        // Example 20 user
        for ($i = 0; $i < 20; $i++) {
            $name = $faker->words(2, true);
            $email = $faker->email;
            $phone = $faker->numerify('##########');
            DB::table('users')->insert([
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'password' => Hash::make('Test@123'),
                'role' => 'user',
                'created_at' => now(),
            ]);
        }
    }
}
