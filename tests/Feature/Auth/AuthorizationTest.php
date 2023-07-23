<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\Authorization;

class AuthorizationTest extends TestCase
{
    /**
     *
     * @test
     *
     * Test with empty data
     * Test with one of the fields missing
     *
     */
    public function itFailsOnMissingCredentials()
    {
        $this->withExceptionHandling();

        $this->postJson('/api/auth/token', [])
            ->assertJsonStructure(['message'])
            ->assertJsonStructure(['errors'])
            ->assertJson([
                "errors" => [
                    "name" => ["The name field is required."],
                    "secret" => ["The secret field is required."],
                ]
            ])
            ->assertUnprocessable();

        // missing password
        $this->postJson('/api/auth/token', [
            'name' => 'random'
        ])
            ->assertJsonStructure(['message'])
            ->assertJsonStructure(['errors'])
            ->assertJson([
                "errors" => [
                    "secret" => ["The secret field is required."],
                ]
            ])
            ->assertUnprocessable();


        // missing name
        $this->postJson('/api/auth/token', [
            'secret' => 'random'
        ])
            ->assertJsonStructure(['message'])
            ->assertJsonStructure(['errors'])
            ->assertJson([
                "errors" => [
                    "name" => ["The name field is required."],
                ]
            ])
            ->assertUnprocessable();
    }

    /**
     *
     * @test
     *
     * Test if the login endpoint denies access from a method different from POST
     *
     */
    public function itFailsOnAccessWithWrongHttpMethod()
    {
        $this->withExceptionHandling();

        $this
            ->getJson('/api/auth/token', [])
            ->assertMethodNotAllowed();


        $this
            ->putJson('/api/auth/token', [])
            ->assertMethodNotAllowed();


        $this
            ->deleteJson('/api/auth/token', [])
            ->assertMethodNotAllowed();
    }
    /**
     *
     * @test
     *
     * Test if the login endpoint fails on usage of wrong credentials
     *
     */
    public function itFailsOnWrongCredentials()
    {
        $this->withExceptionHandling();

        // create an user and send a request to the login route
        $authorization = Authorization::factory()->create();

        // test with wrong credentials
        $this
            ->postJson('/api/auth/token', [
                'name' => $authorization->name,
                'secret' => $authorization->secret . 'salt' . rand(1, 100),
            ])->assertUnauthorized();
    }



    /**
     *
     * @test
     *
     * Test if the login endpoint passes on usage of correct credentials
     *
     */
    public function itPassesOnWrongCredentials(): void
    {
        $this->withExceptionHandling();

        // create an user and send a request to the login route
        $authorization = Authorization::factory()->create();

        // test with correct credentials
        $response = $this->postJson('/api/auth/token', [
            'name' => $authorization->name,
            'secret' => $authorization->secret,
        ]);
        $response->assertOk();
        $response->assertJsonStructure(['token']);
    }

    /**
     *
     * @test
     *
     * Test if the login endpoint passes on usage of correct credentials
     *
     */
    public function itGetsInformationOfTheAuthorizedOrganization()
    {
        $this->withExceptionHandling();

        // create an user and send a request to the login route
        $authorization = Authorization::factory()->create();

        // test with correct credentials
        $response = $this->postJson('/api/auth/token', [
            'name' => $authorization->name,
            'secret' => $authorization->secret,
        ]);

        $token = $response->json()["token"];

        // Test if we're still able to get to the whoami endpoint
        $response = $this->getJson(
            '/api/auth/whoami',
            [
                'Authorization' => 'Bearer ' . $token,
            ]
        )
            ->assertOK();

        $response
            ->assertJsonStructure(['organization'], $response->json())
            ->assertJsonFragment(['name' => $authorization->name])
            ->assertJsonFragment(['secret' => $authorization->secret]);
    }

    /**
     *
     * @test
     * A basic feature test example.
     */
    public function itLogsOut(): void
    {
        $this->withExceptionHandling();

        $authorization = Authorization::factory()->create();

        // Login
        $response = $this->postJson('/api/auth/token', [
            'name' => $authorization->name,
            'secret' => $authorization->secret,
        ])->assertOk();

        $token = $response->json()["token"];

        // Logout
        $this->postJson('/api/auth/logout', [], [
            'Authorization' => 'Bearer ' . $token,
        ])->assertNoContent();
    }
}
