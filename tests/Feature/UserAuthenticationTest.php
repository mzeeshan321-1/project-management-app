<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

test('visitor can access the login page', function () {
    $this->get(route('login'))
        ->assertStatus(200);
});

test('Super Admin can Login', function () {
        $user = User::factory()->userRole('super-admin')->create([
            
            'password' => bcrypt($password = 'password'),
        ]);
        
});

// test('Users without the Authentication and proper role cannot access the dashboard', function () {
//    $user = User::factory()->create();
//    actingAs($user)
//        ->get(route('dashboard'))
//        ->assertStatus(403);
// });
