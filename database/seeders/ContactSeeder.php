<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where("username", "hanep")->first();

        Contact::create([
            'user_id' => $user->id,
            'first_name' => 'Yudis',
            'last_name' => 'Sutisna',
            'email' => 'yudishan26@gmail.com',
            'phone' => '089657933932'
        ]);
    }
}
