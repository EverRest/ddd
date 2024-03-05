<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->refreshDb();
    }

    protected function refreshDb(): void
    {
        $this->artisan('migrate:fresh --env=testing');
        $this->artisan('db:seed --env=testing --class=DatabaseSeeder');
    }
}
