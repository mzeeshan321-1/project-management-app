<?php

use App\Models\User;
use Illuminate\Http\UploadedFile;
use function Pest\Laravel\actingAs;

beforeEach(function () {
    $this->superAdmin = User::factory()->SuperAdmin()->create();
    $this->tanent = User::factory()->Tanent()->create();
    $this->client = User::factory()->Client($this->tanent->tanent->id)->create();
    $this->expert = User::factory()->Expert($this->tanent->tanent->id)->create();
});

describe('Accounts', function () {

    describe('Profile', function () {

        describe('Super Admin', function () {

            test('Super Admin can View Profile', function () {
                actingAs($this->superAdmin)
                    ->get(route('profile.index'))
                    ->assertStatus(200);
            });

            test('Super Admin can change Profile Image', function () {
                actingAs($this->superAdmin)
                    ->put(route('profile.updateImage', $this->superAdmin->id), [
                        'image' => UploadedFile::fake()->image('profile.jpg'),
                    ])
                    ->assertRedirect(route('profile.index'))
                    ->assertStatus(302);
            });
        });

        describe('Tanent', function () {

            test('Tanent can View Profile', function () {
                actingAs($this->tanent)
                    ->get(route('profile.index'))
                    ->assertStatus(200);
            });

            test('Tanent can change Profile Image', function () {
                actingAs($this->tanent)
                    ->put(route('profile.updateImage', $this->tanent->id), [
                        'image' => UploadedFile::fake()->image('profile.jpg'),
                    ])
                    ->assertRedirect(route('profile.index'))
                    ->assertStatus(302);
            });
        });

        describe('Expert', function () {

            test('Expert can View Profile', function () {
                actingAs($this->expert)
                    ->get(route('profile.index'))
                    ->assertStatus(200);
            });

            test('Expert can change Profile Image', function () {
                actingAs($this->expert)
                    ->put(route('profile.updateImage', $this->expert->id), [
                        'image' => UploadedFile::fake()->image('profile.jpg'),
                    ])
                    ->assertRedirect(route('profile.index'))
                    ->assertStatus(302);
            });
        });

        describe('Client', function () {

            test('Client can View Profile', function () {
                actingAs($this->client)
                    ->get(route('profile.index'))
                    ->assertStatus(200);
            });

            test('Client can change Profile Image', function () {
                actingAs($this->client)
                    ->put(route('profile.updateImage', $this->client->id), [
                        'image' => UploadedFile::fake()->image('profile.jpg'),
                    ])
                    ->assertRedirect(route('profile.index'))
                    ->assertStatus(302);
            });
        });
    });

    describe('Settings', function () {

        describe('Profile Settings', function () {

            describe('Super Admin', function () { });

            describe('Tanent', function () { });

            describe('Expert', function () { });

            describe('Client', function () { });
        });

        describe('Password', function () {

            describe('Super Admin', function () { });

            describe('Tanent', function () { });

            describe('Expert', function () { });

            describe('Client', function () { });
        });
    });
});
