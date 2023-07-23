<?php

namespace Tests\Feature\Event;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Event;
use Laravel\Sanctum\Sanctum;
use App\Models\Authorization;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExceptionalTest extends TestCase
{
    public function testTaskCreationValidationErrors()
    {
        $this->withExceptionHandling();

        $organization = Authorization::factory()->create();
        $token = $organization->createToken('test')->plainTextToken;

        $organization->events()->saveMany(Event::factory(10)->make());

        $this->postJson(
            '/api/events',
            [],
            [
                'Authorization' => 'Bearer ' . $token
            ]
        )
            ->assertUnprocessable()
            ->assertJsonStructure(['message'])
            ->assertJsonStructure(['errors'])
            ->assertJson([
                "errors" => [
                    "event_title"       => ["The event title field is required."],
                    "event_start_date"  => ["The event start date field is required."],
                    "event_end_date"    => ["The event end date field is required."],
                ]
            ]);
    }



    public function testIfItDeniesToCreateAnEventWithLongTitle()
    {
        $this->withExceptionHandling();

        $organization = Authorization::factory()->create();
        $token = $organization->createToken('test')->plainTextToken;

        $organization->events()->saveMany(Event::factory(10)->make());

        $this->postJson(
            '/api/events',
            [
                "event_title"       => fake()->sentences(200, true),
                "event_start_date"  => now(),
                "event_end_date"    => now()->addHours(2),
            ],
            [
                'Authorization' => 'Bearer ' . $token
            ]
        )
            ->assertUnprocessable()
            ->assertJsonStructure(['message'])
            ->assertJsonStructure(['errors'])
            ->assertJson([
                "errors" => [
                    "event_title"       => ["The event title field must not be greater than 200 characters."],
                ]
            ]);
    }

    public function testIfItDeniesToCreateAnEventDurationLongerThan12Hours()
    {
        $this->withExceptionHandling();

        $organization = Authorization::factory()->create();
        $token = $organization->createToken('test')->plainTextToken;

        $organization->events()->saveMany(Event::factory(10)->make());

        $this->postJson(
            '/api/events',
            [
                "event_title"       => fake()->sentences(3, true),
                "event_start_date"  => now(),
                "event_end_date"    => now()->addHours(15),
            ],
            [
                'Authorization' => 'Bearer ' . $token
            ]
        )
            ->assertUnprocessable()
            ->assertJsonStructure(['message'])
            ->assertJsonStructure(['errors'])
            ->assertJson([
                "errors" => [
                    "event_duration"       => ["Event duration must be less than 12 hours."],
                ]
            ]);
    }

    public function testIfItDeniesToCreateAnEventNotDatetimeValues()
    {
        $this->withExceptionHandling();

        $organization = Authorization::factory()->create();
        $token = $organization->createToken('test')->plainTextToken;

        $organization->events()->saveMany(Event::factory(10)->make());

        $this->postJson(
            '/api/events',
            [
                "event_title"       => fake()->sentences(3, true),
                "event_start_date"  => 'random text',
                "event_end_date"    => 'random text 2',
            ],
            [
                'Authorization' => 'Bearer ' . $token
            ]
        )
            ->assertUnprocessable()
            ->assertJsonStructure(['message'])
            ->assertJsonStructure(['errors'])
            ->assertJson([
                "errors" => [
                    "event_start_date" => [
                        "The event start date field must be a valid date.",
                        "The event start date field must be a date before event end date."
                    ],
                    "event_end_date" => [
                        "The event end date field must be a valid date.",
                        "The event end date field must be a date after event start date."
                    ]
                ]
            ]);
    }
    public function testTaskNotFound()
    {
        $this->withExceptionHandling();

        $organization = Authorization::factory()->create();
        $token = $organization->createToken('test')->plainTextToken;

        // Assuming a non-existent task ID, try to retrieve it
        $response = $this->getJson(
            "/api/99999999",
            [
                'Authorization' => 'Bearer ' . $token
            ]
        ); // Assuming 999 is a non-existent task ID

        $response->assertNotFound();
        $response->assertJson(['message' => 'Resource not found']);
    }


    public function testUnauthorizedAccess()
    {
        $this->withExceptionHandling();


        $organization = Authorization::factory()->create();
        $organization->events()->saveMany(Event::factory(1)->make());
        $event = $organization->events()->first();

        $organization2 = Authorization::factory()->create();
        $token = $organization2->createToken('test')->plainTextToken;


        $this->getJson(
            "/api/{$event->id}",
            [
                'Authorization' => 'Bearer ' . $token,
            ]
        )
            ->assertForbidden()
            ->assertJson(['message' => 'You do not own this event.']);
    }


    public function testDeleteOtherOrganizationEvent()
    {
        $this->withExceptionHandling();

        $organization = Authorization::factory()->create();
        $organization->events()->saveMany(Event::factory(1)->make());
        $event = $organization->events()->first();

        $organization2 = Authorization::factory()->create();
        $token = $organization2->createToken('test')->plainTextToken;


        $this->deleteJson(
            "/api/{$event->id}",
            [],
            [
                'Authorization' => 'Bearer ' . $token,
            ]
        )
            ->assertForbidden()
            ->assertJson(['message' => 'You do not own this event.']);
    }
}
