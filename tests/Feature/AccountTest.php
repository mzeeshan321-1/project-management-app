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

            describe('Validation', function () {

                test('First Name and Last Name are required', function () {
                    actingAs($this->superAdmin)
                        ->post(route('settings.update'), [
                            'update_profile' => true,
                            'first_name' => '',
                            'last_name' => '',
                            'email' => $this->superAdmin->email,
                        ])
                        ->assertSessionHasErrors(['first_name', 'last_name']);
                });
            });

            describe('Super Admin', function () {

                test('Super Admin can View Profile Settings', function () {
                    actingAs($this->superAdmin)
                        ->get(route('settings.index'))
                        ->assertStatus(200);
                });

                test('Super Admin can Update Profile Settings', function () {
                    actingAs($this->superAdmin)
                        ->post(route('settings.update'), [
                            'first_name' => 'mic',
                            'last_name' => 'mack',
                            'email' => $this->superAdmin->email,
                        ])
                        ->assertRedirect(route('settings.index'))
                        ->assertStatus(302);
                });
            });

            describe('Tanent', function () {

                test('Tanent can View Profile Settings', function () {
                    actingAs($this->tanent)
                        ->get(route('settings.index'))
                        ->assertStatus(200);
                });

                test('Tanent can Update Profile Settings', function () {
                    actingAs($this->tanent)
                        ->post(route('settings.update'), [
                            'first_name' => 'mic',
                            'last_name' => 'mack',
                            'email' => $this->tanent->email,
                        ])
                        ->assertRedirect(route('settings.index'))
                        ->assertStatus(302);
                });
            });

            describe('Expert', function () {

                test('Expert can not View Profile Settings', function () {
                    actingAs($this->expert)
                        ->get(route('settings.index'))
                        ->assertStatus(403);
                });

                test('Expert can not Update Profile Settings', function () {
                    actingAs($this->expert)
                        ->post(route('settings.update'), [])
                        ->assertStatus(403);
                });
            });

            describe('Client', function () {

                test('Client can not View Profile Settings', function () {
                    actingAs($this->client)
                        ->get(route('settings.index'))
                        ->assertStatus(403);
                });

                test('Client can not Update Profile Settings', function () {
                    actingAs($this->client)
                        ->post(route('settings.update'), [])
                        ->assertStatus(403);
                });
            });
        });

        describe('Password', function () {

            describe('Validation', function () {

                test('Current Password and New Password are required', function () {
                    actingAs($this->superAdmin)
                        ->post(route('settings.update'), [
                            'update_password' => true,
                            'current_password' => '',
                            'password' => '',
                            'password_confirmation' => '',
                        ])
                        ->assertSessionHasErrors(['current_password', 'password'], null, 'updatePassword');
                });

                test('New Password and Confirm Password must match', function () {
                    actingAs($this->superAdmin)
                        ->post(route('settings.update'), [
                            'update_password' => true,
                            'current_password' => 'password',
                            'password' => 'newpassword',
                            'password_confirmation' => 'differentpassword',
                        ])
                        ->assertSessionHasErrors(['password'], null, 'updatePassword');
                });

                test('Current Password must be correct', function () {
                    actingAs($this->superAdmin)
                        ->post(route('settings.update'), [
                            'update_password' => true,
                            'current_password' => 'wrongpassword',
                            'password' => 'newpassword',
                            'password_confirmation' => 'newpassword',
                        ])
                        ->assertSessionHasErrors(['current_password'], null, 'updatePassword');
                });

                test('New Password must be at least 8 characters', function () {
                    actingAs($this->superAdmin)
                        ->post(route('settings.update'), [
                            'update_password' => true,
                            'current_password' => 'password',
                            'password' => 'short',
                            'password_confirmation' => 'short',
                        ])
                        ->assertSessionHasErrors(['password'], null, 'updatePassword');
                });
            });

            describe('Super Admin', function () {

                test('Super Admin can change password', function () {
                    actingAs($this->superAdmin)
                        ->post(route('settings.update'), [
                            'update_password' => true,
                            'current_password' => 'password',
                            'password' => 'newpassword',
                            'password_confirmation' => 'newpassword',
                        ])
                        ->assertRedirect(route('settings.index'))
                        ->assertStatus(302);
                });
            });

            describe('Tanent', function () {

                test('Tanent can change password', function () {
                    actingAs($this->tanent)
                        ->post(route('settings.update'), [
                            'update_password' => true,
                            'current_password' => 'password',
                            'password' => 'newpassword',
                            'password_confirmation' => 'newpassword',
                        ])
                        ->assertRedirect(route('settings.index'))
                        ->assertStatus(302);
                });
            });

            describe('Expert', function () {

                test('Expert can not change password', function () {
                    actingAs($this->expert)
                        ->post(route('settings.update'), [])
                        ->assertStatus(403);
                });
            });

            describe('Client', function () {

                test('Client can not change password', function () {
                    actingAs($this->client)
                        ->post(route('settings.update'), [])
                        ->assertStatus(403);
                });
            });
        });
    });
});
