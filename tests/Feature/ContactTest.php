<?php

namespace Tests\Feature;

use App\Models\Contact;
use Database\Seeders\ContactSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ContactTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testCreateSuccess()
    {
        $this->seed([UserSeeder::class]);
        $this->post("/api/contact", [
            "first_name" => "Furqon",
            "last_name" => "August",
            "email" => "furqonaugust@mail.com",
            "phone" => "08123123",
        ], [
            "Authorization" => "test"
        ])->assertStatus(201)->assertJson([
            "data"  => [
                "first_name" => "Furqon",
                "last_name" => "August",
                "email" => "furqonaugust@mail.com",
                "phone" => "08123123",
            ]
        ]);
    }

    public function testCreateFailed()
    {
        $this->seed([UserSeeder::class]);
        $this->post("/api/contact", [
            "first_name" => "",
            "last_name" => "August",
            "email" => "furqonaugust",
            "phone" => "08123123",
        ], [
            "Authorization" => "test"
        ])->assertStatus(400)->assertJson([
            "errors"  => [
                "first_name" => [
                    "The first name field is required."
                ],
                "email" =>  [
                    "The email field must be a valid email address."
                ],
            ]
        ]);
    }

    public function testCreateUnauthorized()
    {
        $this->seed([UserSeeder::class]);
        $this->post("/api/contact", [
            "first_name" => "",
            "last_name" => "August",
            "email" => "furqonaugust",
            "phone" => "08123123",
        ], [
            "Authorization" => "tes1t"
        ])->assertStatus(401)->assertJson([
            "errors"  => [
                "message" => ["unauthorized"]
            ]
        ]);
    }

    public function testGetSuccess()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();

        $this->get('/api/contacts/' . $contact->id, [
            "Authorization" => "test"
        ])->assertStatus(200)->assertJson([
            "data"  => [
                "first_name"    => "test",
                "last_name"    => "test",
                "email"    => "test@mail.com",
                "phone"    => "0812312",
            ]
        ]);
    }

    public function testGetNotFound()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();

        $this->get('/api/contacts/' . ($contact->id + 1), [
            "Authorization" => "test"
        ])->assertStatus(404)->assertJson([
            "errors"  => [
                "message"    => ["not found"],
            ]
        ]);
    }

    public function testGetOtherUserContact()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();

        $this->get('/api/contacts/' . ($contact->id), [
            "Authorization" => "test2"
        ])->assertStatus(404)->assertJson([
            "errors"  => [
                "message"    => ["not found"],
            ]
        ]);
    }

    public function testUpdateSuccess() {}

    public function testUpdateFailed() {}
}
