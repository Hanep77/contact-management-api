<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SearchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where("username", "hanep")->first();

        for ($i = 0; $i < 10; $i++) {
            Contact::create([
                'user_id' => $user->id,
                'first_name' => 'Yudis' . $i,
                'last_name' => 'Sutisna' . $i,
                'email' =>  $i . 'yudishan26@gmail.com',
                'phone' => '08965793393' . $i
            ]);
        }
    }
}
