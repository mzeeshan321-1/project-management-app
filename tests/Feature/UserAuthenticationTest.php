<?php

use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

describe('User Authentication - Validation', function () {

    it('fails when email is invalid', function () {
        post(route('login'), [
            'email' => 'invalid-email',
            'password' => 'password123',
        ])->assertSessionHasErrors(['email']);
    });

    it('fails when password is shorter then minimum length', function () {
        post(route('login'), [
            'email' => 'user@example.com',
            'password' => '1234567',
        ])->assertSessionHasErrors(['password']);
    });

    it('fails when both fields are empty', function () {
        post(route('login'), [
            'email' => '',
            'password' => '',
        ])->assertSessionHasErrors(['email', 'password']);
    });

    it('fails when password is wrong or incorrect', function () {
        $user = User::factory()->create([
            'password' => bcrypt('password'),
        ]);
        post(route('login'), [
            'email' => $user->email,
            'password' => 'wrong-pass',
        ]);

        expect(session('flasher::envelopes'))->not->toBeEmpty();
    });

    test('Unauthenticated and Unauthorized user can not login', function () {
        $randomUser = User::factory()->create();
        actingAs($randomUser)
            ->get(route('dashboard'))
            ->assertStatus(403);
    });
});

describe('User Authentication - Login', function () {

    test('visitor can access the login page', function () {
        get(route('login'))
            ->assertStatus(200);
    });

    test('Super Admin can login', function () {
        $superAdmin = User::factory()->superAdmin()->create();

        actingAs($superAdmin)
            ->get(route('dashboard'))
            ->assertSee('Dashboard')
            ->assertStatus(200);
    });

    test('Tenant can login', function () {
        $tanent = User::factory()->Tanent()->create();

        actingAs($tanent)
            ->get(route('dashboard'))
            ->assertSee('Dashboard')
            ->assertStatus(200);
    });

    test("Expert assigned by Tenant can login", function () {
        $tanent = User::factory()->Tanent()->create();

        $expert = User::factory()->Expert($tanent->tanent->id)->create();
        actingAs($expert)
            ->get(route('dashboard'))
            ->assertSee('Dashboard')
            ->assertStatus(200);
    });

    test("Client assigned by Tenant can login", function () {
        $tanent = User::factory()->Tanent()->create();

        $client = User::factory()->Client($tanent->tanent->id)->create();
        actingAs($client)
            ->get(route('dashboard'))
            ->assertSee('Dashboard')
            ->assertStatus(200);
    });
});

describe('User Authentication - Logout', function () {

    test('Authenticated user can logout', function () {
        $user = User::factory()->create();

        actingAs($user)
            ->get(route('logout'))
            ->assertRedirect(route('login'));

        expect(auth()->guest())->toBeTrue();
    });
});
