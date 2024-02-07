<?php

namespace Tests\Feature;

use App\Models\Contact;
use Database\Seeders\ContactSeeder;
use Database\Seeders\UserSeeder;
use Tests\TestCase;

class ContactTest extends TestCase
{
    public function testCreateSucces(): void
    {
        $this->seed(UserSeeder::class);

        $this->post('/api/contact', [
            "first_name" => "Yudis",
            "last_name" => "Sutisna",
            "email" => "yudishan26@gmail.com",
            "phone" => "089657933932"
        ], [
            "Authorization" => "test"
        ])->assertStatus(201)->assertJson([
            "data" => [
                "first_name" => "Yudis",
                "last_name" => "Sutisna",
                "email" => "yudishan26@gmail.com",
                "phone" => "089657933932"
            ]
        ]);
    }

    public function testCreateFailed(): void
    {
        $this->seed(UserSeeder::class);

        $this->post('/api/contact', [
            "first_name" => "",
            "last_name" => "Sutisna",
            "email" => "yudishan26@gmail.com",
            "phone" => "089657933932"
        ], [
            "Authorization" => "test"
        ])->assertStatus(400)->assertJson([
            "errors" => [
                "first_name" => [
                    "The first name field is required."
                ]
            ]
        ]);
    }

    public function testUnauthorized(): void
    {
        $this->seed(UserSeeder::class);

        $this->post('/api/contact', [
            "first_name" => "Yudis",
            "last_name" => "Sutisna",
            "email" => "yudishan26@gmail.com",
            "phone" => "089657933932"
        ], [
            "Authorization" => "tes"
        ])->assertStatus(401)->assertJson([
            "errors" => [
                "message" => [
                    "unauthorized"
                ]
            ]
        ]);
    }

    public function testGetContact()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);

        $contact = Contact::query()->limit(1)->first();

        $this->get('/api/contacts/' . $contact->id, [
            "Authorization" => "test"
        ])->assertStatus(200)->assertJson([
            "data" => [
                "id" => $contact->id,
                "first_name" => "Yudis",
                "last_name" => "Sutisna",
                "email" => "yudishan26@gmail.com",
                "phone" => "089657933932"
            ]
        ]);
    }

    public function testGetNotFound()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);

        $contact = Contact::query()->limit(1)->first();

        $this->get('/api/contacts/' . 2, [
            "Authorization" => "test"
        ])->assertStatus(404)->assertJson([
            "errors" => [
                "message" => [
                    "not found"
                ]
            ]
        ]);
    }

    public function testGetOtherUserContact()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);

        $contact = Contact::query()->limit(1)->first();

        $this->get('/api/contacts/' . $contact->id, [
            "Authorization" => "tokenuu"
        ])->assertStatus(404)->assertJson([
            "errors" => [
                "message" => [
                    "not found"
                ]
            ]
        ]);
    }

    public function testUpdateContact()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);

        $contact = Contact::query()->limit(1)->first();

        $this->put('/api/contacts/' . $contact->id, [
            "first_name" => "hanep",
            "last_name" => "",
            "email" => "",
            "phone" => "",
        ], [
            "Authorization" => "test"
        ])->assertStatus(200)->assertJson([
            "data" => [
                "id" => $contact->id,
                "first_name" => "hanep",
                "last_name" => null,
                "email" => null,
                "phone" => null
            ]
        ]);
    }

    public function testUpdateValidationError()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);

        $contact = Contact::query()->limit(1)->first();

        $this->put('/api/contacts/' . $contact->id, [
            "first_name" => null
        ], [
            "Authorization" => "test"
        ])->assertStatus(400)->assertJson([
            "errors" => [
                "first_name" => [
                    "The first name field is required."
                ]
            ]
        ]);
    }

    public function testDeleteContact()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);

        $contact = Contact::query()->limit(1)->first();

        $this->delete('/api/contacts/' . $contact->id, [], [
            "Authorization" => "test"
        ])->assertStatus(200)->assertJson([
            "data" => true
        ]);
    }

    public function testFailedDeleteContact()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);

        $contact = Contact::query()->limit(1)->first();

        $this->delete('/api/contacts/' . $contact->id + 1, [], [
            "Authorization" => "test"
        ])->assertStatus(404)->assertJson([
            "errors" => [
                "message" => [
                    "not found"
                ]
            ]
        ]);
    }

    public function testDeleteOtherUserContact()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);

        $contact = Contact::query()->limit(1)->first();

        $this->delete('/api/contacts/' . $contact->id, [], [
            "Authorization" => "tokenuu"
        ])->assertStatus(404)->assertJson([
            "errors" => [
                "message" => [
                    "not found"
                ]
            ]
        ]);
    }
}
