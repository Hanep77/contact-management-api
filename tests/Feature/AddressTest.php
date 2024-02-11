<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Contact;
use Database\Seeders\AddressSeeder;
use Database\Seeders\ContactSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class AddressTest extends TestCase
{
    public function testCreateAddress(): void
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();

        $this->post("/api/contacts/$contact->id/addresses", [
            "street" => "testStreet",
            "city" => "testCity",
            "province" => "testProvince",
            "country" => "Indonesia",
            "postal_code" => "testPostalCode"
        ], [
            "Authorization" => "test"
        ])->assertStatus(201)
            ->assertJson([
                "data" => [
                    "street" => "testStreet",
                    "city" => "testCity",
                    "province" => "testProvince",
                    "country" => "Indonesia",
                    "postal_code" => "testPostalCode"
                ]
            ]);
    }

    public function testFailedCreateAddress()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();

        $this->post("/api/contacts/$contact->id/addresses", [
            "country" => ""
        ], [
            "Authorization" => "test"
        ])->assertStatus(400)
            ->assertJson([
                "errors" => [
                    "country" => [
                        "The country field is required."
                    ]
                ]
            ]);
    }

    public function testCreateAddressContactNotFound()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();

        $this->post("/api/contacts/1/addresses", [
            "country" => "Indonesia"
        ], [
            "Authorization" => "test"
        ])->assertStatus(404)
            ->assertJson([
                "errors" => [
                    "message" => [
                        "not found"
                    ]
                ]
            ]);
    }

    public function testGetAddress()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);
        $address = Address::query()->limit(1)->first();

        $this->get("/api/contacts/" . $address->contact->id . "/addresses/ " . $address->id, [
            "Authorization" => "test"
        ])->assertStatus(200)
            ->assertJson([
                "data" => [
                    "street" => "testStreet",
                    "city" => "testCity",
                    "province" => "testProvince",
                    "country" => "Indonesia",
                    "postal_code" => "testPostalCode"
                ]
            ]);
    }

    public function testGetAddressNotFound()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);
        $address = Address::query()->limit(1)->first();

        $this->get("/api/contacts/" . $address->contact->id . "/addresses/ " . $address->id + 1, [
            "Authorization" => "test"
        ])->assertStatus(404)
            ->assertJson([
                "errors" => [
                    "message" => [
                        "not found"
                    ]
                ]
            ]);
    }

    public function testUpdateAddress(): void
    {
        $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);
        $address = Address::query()->limit(1)->first();

        $this->put("/api/contacts/" . $address->contact->id . "/addresses/ " . $address->id, [
            "street" => "testStreetUpdate",
            "city" => "testCityUpdate",
            "province" => "testProvinceUpdate",
            "country" => "IndonesiaUpdate",
            "postal_code" => "testPostalCodeUpdate"
        ], [
            "Authorization" => "test"
        ])->assertStatus(200)->assertJson([
            "data" => [
                "street" => "testStreetUpdate",
                "city" => "testCityUpdate",
                "province" => "testProvinceUpdate",
                "country" => "IndonesiaUpdate",
                "postal_code" => "testPostalCodeUpdate"
            ]
        ]);
    }

    public function testUpdateAddressFailed(): void
    {
        $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);
        $address = Address::query()->limit(1)->first();

        $this->put("/api/contacts/" . $address->contact->id . "/addresses/ " . $address->id, [
            "street" => "testStreetUpdate",
            "city" => "testCityUpdate",
            "province" => "testProvinceUpdate",
            "country" => "",
            "postal_code" => "testPostalCodeUpdate"
        ], [
            "Authorization" => "test"
        ])->assertStatus(400)
            ->assertJson([
                "errors" => [
                    "country" => [
                        "The country field is required."
                    ]
                ]
            ]);
    }

    public function testUpdateAddressNotFound(): void
    {
        $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);
        $address = Address::query()->limit(1)->first();

        $this->put("/api/contacts/" . $address->contact->id . "/addresses/ " . $address->id + 1, [
            "street" => "testStreetUpdate",
            "city" => "testCityUpdate",
            "province" => "testProvinceUpdate",
            "country" => "IndonesiaUpdate",
            "postal_code" => "testPostalCodeUpdate"
        ], [
            "Authorization" => "test"
        ])->assertStatus(404)
            ->assertJson([
                "errors" => [
                    "message" => [
                        "not found"
                    ]
                ]
            ]);
    }

    public function testDelete()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);
        $address = Address::query()->limit(1)->first();

        $this->delete("/api/contacts/" . $address->contact->id . "/addresses/ " . $address->id, [], [
            "Authorization" => "test"
        ])->assertStatus(200)
            ->assertJson([
                "data" => true
            ]);
    }

    public function testDeleteNotFound()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);
        $address = Address::query()->limit(1)->first();

        $this->delete("/api/contacts/" . $address->contact->id . "/addresses/ " . $address->id + 1, [], [
            "Authorization" => "test"
        ])->assertStatus(404)
            ->assertJson([
                "errors" => [
                    "message" => [
                        "not found"
                    ]
                ]
            ]);
    }

    public function testListSuccess()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);
        $contact = Contact::query()->limit(1)->first();

        $this->get("/api/contacts/" . $contact->id . "/addresses", [
            "Authorization" => "test"
        ])->assertStatus(200)
            ->assertJson([
                "data" => [
                    [
                        "street" => "testStreet",
                        "city" => "testCity",
                        "province" => "testProvince",
                        "country" => "Indonesia",
                        "postal_code" => "testPostalCode"
                    ]
                ]
            ]);
    }

    public function testListNotFound()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);
        $contact = Contact::query()->limit(1)->first();

        $this->get("/api/contacts/" . $contact->id + 1 . "/addresses", [
            "Authorization" => "test"
        ])->assertStatus(404)
            ->assertJson([
                "errors" => [
                    "message" => [
                        "not found"
                    ]
                ]
            ]);
    }
}
