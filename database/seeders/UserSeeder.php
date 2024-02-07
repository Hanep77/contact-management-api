<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Yudis Sutisna',
            'username' => 'hanep',
            'password' => Hash::make('rahasia'),
            'token' => 'test'
        ]);

        User::create([
            'name' => 'uu',
            'username' => 'uu',
            'password' => Hash::make('rahasia'),
            'token' => 'tokenuu'
        ]);
    }
}
