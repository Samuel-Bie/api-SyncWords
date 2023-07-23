<?php

namespace Tests\Feature\Event;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Event;
use App\Models\Authorization;

class SuccessTest extends TestCase
{
    public function testEventListing()
    {
        $organization = Authorization::factory()->create();
        $token = $organization->createToken('test')->plainTextToken;

        $organization->events()->saveMany(Event::factory(10)->make());

        $this->getJson(
            '/api',
            [
                'Authorization' => 'Bearer ' . $token
            ]
        )
        ->assertOk()
        ->assertJsonIsArray('data')
        ->assertJsonCount(10, 'data')
        ->assertJsonStructure([
            'data' => [
                '*' => [
                     'id',
                     'event_title',
                     'event_start_date',
                     'event_end_date'
                ]
            ]
        ]);
    }

    public function testEventCreationSuccessfully()
    {
        $thursday = $start  = now()->endOfWeek(Carbon::MONDAY)->addDays(4)->midDay();
        $end = (new Carbon($thursday))->addHour();

        $organization = Authorization::factory()->create();
        $token = $organization->createToken('test')->plainTextToken;

        $data = [
            'event_title' => 'Interview at Sync Works',
            'event_start_date' => $start,
            'event_end_date' => $end,
        ];

        $this->postJson('api/events', $data, [
            'Authorization' => 'Bearer ' . $token
        ])
        ->assertCreated()
        ->assertJson([
            'event_title' => $data['event_title'],
            'organization_id' => $organization->id,
        ]);

        // Optionally, you can check if the task was actually added to the database
        $this->assertDatabaseHas('events', ['event_title' => $data['event_title']]);
    }

    public function testEventSuccessfulReading()
    {
        $organization = Authorization::factory()->create();
        $token = $organization->createToken('test')->plainTextToken;

        $organization->events()->saveMany(Event::factory(1)->make());

        $event = $organization->events()->first();

        $this->getJson("/api/{$event->id}", [
            'Authorization' => 'Bearer ' . $token
        ])
            ->assertOk()
            ->assertJsonStructure(['data'])
            ->assertJsonFragment(['event_title' => $event->event_title]);
    }

    public function testEventFullUpdating()
    {
        $organization = Authorization::factory()->create();
        $token = $organization->createToken('test')->plainTextToken;

        $organization->events()->saveMany(Event::factory(1)->make());

        $event = $organization->events()->first();

        $data = [
            "event_title" => "Stand up comedy show",
            "event_start_date" => "2023-06-06 08:30:30",
            "event_end_date" => "2023-06-06 09:30:30"
        ];

        $this->putJson(
            "/api/{$event->id}",
            $data,
            [
                'Authorization' => 'Bearer ' . $token
            ]
        )
            ->assertAccepted()
            ->assertJson($data);
        // Optionally, you can check if the task was actually updated in the database
        $this->assertDatabaseHas('events', $data);
    }

    public function testEventPartialUpdating()
    {
        $organization = Authorization::factory()->create();
        $token = $organization->createToken('test')->plainTextToken;
        $organization->events()->saveMany(Event::factory(1)->make(
            [
                "event_start_date" => "2023-06-06 11:30:30",
                "event_end_date" => "2023-06-06 13:30:30"
            ]
        ));
        $event = $organization->events()->first();

        $data_title = [
            "event_title" => "Stand up comedy show",
        ];
        $data_start_date = [
            "event_start_date" => "2023-06-06 08:30:30",
        ];
        $data_end_date = [
            "event_end_date" => "2023-06-06 09:30:30"
        ];


        // Just update event title
        $this->patchJson(
            "/api/{$event->id}",
            $data_title,
            [
                'Authorization' => 'Bearer ' . $token
            ]
        )
            ->assertAccepted()
            ->assertJson($data_title);

        // Just update start date
        $this->patchJson(
            "/api/{$event->id}",
            $data_start_date,
            [
                'Authorization' => 'Bearer ' . $token
            ]
        )
            ->assertAccepted()
            ->assertJson($data_start_date);

        // Just update end date
        $this->patchJson(
            "/api/{$event->id}",
            $data_end_date,
            [
                'Authorization' => 'Bearer ' . $token
            ]
        )
            ->assertAccepted()
            ->assertJson($data_end_date);

        // Verify if all updates have been made successfuly
        $this->assertDatabaseHas('events', [
            'event_start_date' => $data_start_date["event_start_date"],
            'event_title' => $data_title['event_title'],
            'event_end_date' => $data_end_date['event_end_date']
        ]);
    }


    public function testTaskDeletion()
    {
        $organization = Authorization::factory()->create();
        $token = $organization->createToken('test')->plainTextToken;
        $organization->events()->saveMany(Event::factory(1)->make(
            [
                "event_start_date" => "2023-06-06 11:30:30",
                "event_end_date" => "2023-06-06 13:30:30"
            ]
        ));
        $event = $organization->events()->first();
        $this->deleteJson(
            "/api/{$event->id}",
            [],
            [
                'Authorization' => 'Bearer ' . $token
            ]
        )
            ->assertNoContent()
            ->assertContent('');
        // Optionally, you can check if the task was actually deleted from the database
        $this->assertDatabaseMissing('events', ['id' => $event->id]);
    }
}
