<?php

use App\Models\Tanent;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use function Pest\Laravel\actingAs;

beforeEach(function () {
    $this->superAdmin = User::factory()->SuperAdmin()->create();
});

describe('Tanent Management - Super Admin', function () {

    test('Super Admin can Access List of Tanents', function () {
        actingAs($this->superAdmin)
            ->get(route('middleman.index'))
            ->assertSee('Tenants')
            ->assertStatus(200);
    });

    test('Super Admin can see All the Existing Tanents', function () {
        $tanents = User::factory(3)->Tanent()->create();
        actingAs($this->superAdmin)
            ->get(route('middleman.index'))
            ->assertSeeText($tanents[0]->first_name)
            ->assertSeeText($tanents[1]->first_name)
            ->assertSeeText($tanents[2]->first_name)
            ->assertStatus(200);
    });

    test('Super Admin can Access Tanent Creation page', function () {
        actingAs($this->superAdmin)
            ->get(route('middleman.create'))
            ->assertSee('Create Tenant Account')
            ->assertStatus(200);
    });

    test('Super Admin can Create a New Tanent', function () {
        $UserRecord = [
            'first_name' => 'Test',
            'last_name' => 'Tanent',
            'email' => 'testTanent@app.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'address' => 'Test Address',
            'contact' => '1234567890',
            'status' => 'active',
            'image' => UploadedFile::fake()->image('avatar.jpg'),
        ];

        actingAs($this->superAdmin)
            ->post(route('middleman.store'), $UserRecord)
            ->assertRedirect(route('middleman.index'));

        $NewUser = User::where('email', $UserRecord['email'])->first();
        expect($NewUser)->not->toBeNull();
        expect(Hash::check('password', $NewUser->password))->toBeTrue();

        $NewTanent = Tanent::where('user_id', $NewUser->id)->first();
        expect($NewTanent)->not->toBeNull();

        $this->assertDatabaseHas('tanents', [
            'user_id' => $NewUser->id,
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'testTanent@app.com'
        ]);
    });

    test('Super Admin can Access Edit Tanent page', function () {
        $tanent = User::factory()->Tanent()->create();
        actingAs($this->superAdmin)
            ->get(route('middleman.edit', $tanent->id))
            ->assertSeeText('Edit Tenant Account')
            ->assertStatus(200);
    });

    test('Super Admin can Edit a Tanent', function () {
        $tanent = User::factory()->Tanent()->create([
            'address' => 'Test Address',
            'status' => 'active',
        ]);
        $UpdatedTanent = [
            'first_name' => 'Updated',
            'last_name' => 'Tanent',
            'email' => 'updatedTanent@app.com',
            'password' => 'updatedPassword',
            'password_confirmation' => 'updatedPassword',
            'address' => 'Updated Address',
            'status' => 'active',
        ];

        actingAs($this->superAdmin)
            ->put(route('middleman.update', $tanent->id), $UpdatedTanent)
            ->assertRedirect(route('middleman.index'));

        $UpdatedUser = User::where('email', $UpdatedTanent['email'])->first();
        expect($UpdatedUser->first_name)->toBe('Updated');
        expect($UpdatedUser->email)->toBe('updatedTanent@app.com');
        expect(Hash::check('updatedPassword', $UpdatedUser->password))->toBeTrue();
    });

    test('Super Admin can Delete a Tanent', function () {
        $tanent = User::factory()->Tanent()->create();
        actingAs($this->superAdmin)
            ->delete(route('middleman.delete', $tanent->id))
            ->assertRedirect(route('middleman.index'));
    });
});

describe('Tanent Management - Tanent', function () {

    test('Tanent can not Access List of Tanents', function () {
        $tanent = User::factory()->Tanent()->create();
        actingAs($tanent)
            ->get(route('middleman.index'))
            ->assertStatus(403);
    });

    test('Tanent can not Access Tanent Creation page', function () {
        $tanent = User::factory()->Tanent()->create();
        actingAs($tanent)
            ->get(route('middleman.create'))
            ->assertStatus(403);
    });

    test('Tanent can not Access Edit Tanent', function () {
        $tanent = User::factory()->Tanent()->create();
        $NewTanent = User::factory()->Tanent()->create();
        actingAs($tanent)
            ->get(route('middleman.edit', $NewTanent->id))
            ->assertStatus(403);
    });

    test('Tanent can not Delete Tanent', function () {
        $tanent = User::factory()->Tanent()->create();
        $NewTanent = User::factory()->Tanent()->create();
        actingAs($tanent)
            ->delete(route('middleman.delete', $NewTanent->id))
            ->assertStatus(403);
    });
});

describe('Tanent Management - Expert', function () {

    test('Expert can Access List of Tanent and Allowed to See Only his Tanent', function () {
        $tanent = User::factory()->Tanent()->create();
        $expert = User::factory()->Expert($tanent->tanent->id)->create();
        $otherTanents = User::factory(2)->Tanent()->create();
        actingAs($expert)
            ->get(route('middleman.index'))
            ->assertStatus(200)
            ->assertDontSee($otherTanents[0]->first_name)
            ->assertDontSee($otherTanents[1]->first_name)
            ->assertSee($tanent->first_name);
    });

    test('Expert can not Create New Tanents', function () {
        $tanent = User::factory()->Tanent()->create();
        $expert = User::factory()->Expert($tanent->tanent->id)->create();
        actingAs($expert)
            ->get(route('middleman.create'))
            ->assertStatus(403);
    });

    test('Exoert can not Edit Tanents', function() {
        $tanent = User::factory()->Tanent()->create();
        $expert = User::factory()->Expert($tanent->tanent->id)->create();
        actingAs($expert)
            ->get(route('middleman.edit', $tanent->id))
            ->assertStatus(403);
    });

    test('Expert can not delete Tanents', function() {
        $tanent = User::factory()->Tanent()->create();
        $expert = User::factory()->Expert($tanent->tanent->id)->create();
        actingAs($expert)
            ->delete(route('middleman.delete', $tanent->id))
            ->assertStatus(403);
    });
});

describe('Tanent Management - Client', function () {

    test('Client can Access List of Tanent and Allowed to See Only his Tanent', function () {
        $tanent = User::factory()->Tanent()->create();
        $client = User::factory()->Client($tanent->tanent->id)->create();
        $otherTanents = User::factory(2)->Tanent()->create();
        actingAs($client)
            ->get(route('middleman.index'))
            ->assertStatus(200)
            ->assertDontSee($otherTanents[0]->first_name)
            ->assertDontSee($otherTanents[1]->first_name)
            ->assertSee($tanent->first_name);
    });

    test('Client can not Create New Tanents', function () {
        $tanent = User::factory()->Tanent()->create();
        $client = User::factory()->Client($tanent->tanent->id)->create();
        actingAs($client)
            ->get(route('middleman.create'))
            ->assertStatus(403);
    });

    test('Client can not Edit Tanents', function() {
        $tanent = User::factory()->Tanent()->create();
        $client = User::factory()->Client($tanent->tanent->id)->create();
        actingAs($client)
            ->get(route('middleman.edit', $tanent->tanent->id))
            ->assertStatus(403);
    });

    test('Client can not delete Tanents', function() {
        $tanent = User::factory()->Tanent()->create();
        $client = User::factory()->Client($tanent->tanent->id)->create();
        actingAs($client)
            ->delete(route('middleman.delete', $tanent->tanent->id))
            ->assertStatus(403);
    });
});

describe('Tanent Management - Validation', function () {

    test('Validation fails when required fields are Empty', function () {
        $UserRecord = [
            'first_name' => '',
            'last_name' => '',
            'email' => '',
            'password' => '',
            'password_confirmation' => '',
            'status' => 'active',
        ];

        actingAs($this->superAdmin)
            ->post(route('middleman.store'), $UserRecord);

        expect(session('flasher::envelopes'))->not->toBeEmpty();
    });

    test('Validation fails when Password and/or Confirm Password does not match', function () {
        $UserRecord = [
            'first_name' => 'Test',
            'last_name' => 'Tanent',
            'email' => 'testTanent@app.com',
            'password' => 'password',
            'password_confirmation' => 'wrong-password',
            'status' => 'active',
        ];

        actingAs($this->superAdmin)
            ->post(route('middleman.store'), $UserRecord);

        expect(session('flasher::envelopes'))->not->toBeEmpty();
    });

    test('Validation fails when User already exists with same email', function () {
        User::factory()->create([
            'email' => 'testTanent@app.com',
        ]);

        $userRecord = [
            'first_name' => 'Test',
            'last_name' => 'Tanent',
            'email' => 'testTanent@app.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'status' => 'active',
        ];
        actingAs($this->superAdmin)
            ->post(route('middleman.store'), $userRecord);

        expect(session('flasher::envelopes'))->not->toBeEmpty();
    });
});