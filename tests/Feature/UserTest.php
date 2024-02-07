<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\UserSeeder;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testRegisterSuccess(): void
    {
        $this->post('/api/users', [
            "name" => "Yudis Sutisna",
            "username" => "hanep",
            "password" => "rahasia"
        ])->assertStatus(201)->assertJson([
            "data" => [
                "name" => "Yudis Sutisna",
                "username" => "hanep",
            ]
        ]);
    }

    public function testRegisterFailed(): void
    {
        $this->post('/api/users', [
            "name" => "",
            "username" => "",
            "password" => ""
        ])->assertStatus(400)->assertJson([
            "errors" => [
                "name" => [
                    "The name field is required.",
                ],
                "username" => [
                    "The username field is required.",
                ],
                "password" => [
                    "The password field is required.",
                ]
            ]
        ]);
    }

    public function testUserAlreadyExist(): void
    {
        $this->post('/api/users', [
            "name" => "Yudis Sutisna",
            "username" => "hanep",
            "password" => "rahasia"
        ])->assertStatus(201)->assertJson([
            "data" => [
                "name" => "Yudis Sutisna",
                "username" => "hanep",
            ]
        ]);

        $this->post('/api/users', [
            "name" => "Yudis Sutisna",
            "username" => "hanep",
            "password" => "rahasia"
        ])->assertStatus(400)->assertJson([
            "errors" => [
                "username" => ["The username has already been taken."]
            ]
        ]);
    }

    public function testLoginSuccess(): void
    {
        $this->seed(UserSeeder::class);

        $this->post('/api/users/login', [
            "username" => "hanep",
            "password" => "rahasia"
        ])->assertStatus(200)->assertJson([
            "data" => [
                "name" => "Yudis Sutisna",
                "username" => "hanep",
            ]
        ]);
    }

    public function testLoginFailed(): void
    {
        $this->seed(UserSeeder::class);

        $this->post('/api/users/login', [
            "username" => "hanep",
            "password" => "jdsklfj"
        ])->assertStatus(401)->assertJson([
            "errors" => [
                "message" => "username or password wrong",
            ]
        ]);

        $this->post('/api/users/login', [
            "username" => "",
            "password" => ""
        ])->assertStatus(400)->assertJson([
            "errors" => [
                "username" => [
                    "The username field is required.",
                ],
                "password" => [
                    "The password field is required.",
                ]
            ]
        ]);
    }

    public function testGetSuccess()
    {
        $this->seed(UserSeeder::class);

        $this->get('/api/users/current', [
            'Authorization' => 'test'
        ])->assertStatus(200)
            ->assertJson([
                "data" => [
                    "username" => 'hanep',
                    "token" => 'test'
                ]
            ]);
    }

    public function testGetUnauthorized()
    {
        $this->seed(UserSeeder::class);

        $this->get('/api/users/current', [])->assertStatus(401)
            ->assertJson([
                "errors" => [
                    "message" => [
                        "unauthorized"
                    ]
                ]
            ]);
    }

    public function testGetInvalidToken()
    {
        $this->seed(UserSeeder::class);

        $this->get('/api/users/current', [
            "Authorization" => "salah"
        ])->assertStatus(401)
            ->assertJson([
                "errors" => [
                    "message" => [
                        "unauthorized"
                    ]
                ]
            ]);
    }

    public function testUpdateNameSuccess()
    {
        $this->seed(UserSeeder::class);

        $this->patch(
            '/api/users/current',
            [
                "name" => "yhanep"
            ],
            [
                "Authorization" => "test"
            ]
        )->assertStatus(200)->assertJson([
            "data" => [
                "name" => "yhanep",
                "username" => "hanep",
            ]
        ]);
    }

    public function testUpdateUsernameSuccess()
    {
        $this->seed(UserSeeder::class);

        $this->patch(
            '/api/users/current',
            [
                "username" => "hanep77"
            ],
            [
                "Authorization" => "test"
            ]
        )->assertStatus(200)->assertJson([
            "data" => [
                "name" => "Yudis Sutisna",
                "username" => "hanep77",
            ]
        ]);
    }

    public function testUpdatePasswordSuccess()
    {
        $this->seed(UserSeeder::class);
        $olduser = User::where("username", "hanep")->first();

        $this->patch(
            '/api/users/current',
            [
                "password" => "baru",
            ],
            [
                "Authorization" => "test"
            ]
        )->assertStatus(200)->assertJson([
            "data" => [
                "name" => "Yudis Sutisna",
                "username" => "hanep",
            ]
        ]);

        $newuser = User::where("username", "hanep")->first();
        self::assertNotEquals($olduser->password, $newuser->password);
    }

    public function testLogoutSuccess()
    {
        $this->seed(UserSeeder::class);

        $this->delete(
            '/api/users/logout',
            [],
            [
                "Authorization" => "test"
            ]
        )->assertStatus(200)->assertJson([
            "data" => true
        ]);
    }

    public function testLogoutFailed()
    {
        $this->seed(UserSeeder::class);

        $this->delete(
            '/api/users/logout',
            [],
            [
                "Authorization" => "salah"
            ]
        )->assertStatus(401)->assertJson([
            "errors" => [
                "message" => [
                    "unauthorized"
                ]
            ]
        ]);
    }
}
