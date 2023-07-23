<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    // create the setUp method
    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }
}
