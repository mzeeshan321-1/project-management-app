<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Disable foreign key checks during migration
        \DB::statement('PRAGMA foreign_keys = OFF;');
        
        // Run migrations without VACUUM
        $this->artisan('migrate', ['--force' => true]);
        
        // Re-enable foreign key checks
        \DB::statement('PRAGMA foreign_keys = ON;');
        
        // Run seeders
        $this->artisan('db:seed', ['--class' => \Database\Seeders\RolePermissionSeeder::class]);
    }
}
