<?php

use App\Models\Client;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use function Pest\Laravel\actingAs;

beforeEach(function () {
    $this->superAdmin = User::factory()->SuperAdmin()->create();
    $this->tanent = User::factory()->Tanent()->create();
});

describe('Client Management - Tanent - Validation', function () {

    test('Validation fails when required fields are Empty', function () {
        $UserRecord = [
            'first_name' => '',
            'last_name' => '',
            'email' => '',
            'password' => '',
            'password_confirmation' => '',
            'status' => 'active',
        ];

        actingAs($this->tanent)
            ->post(route('clients.store'), $UserRecord);

        expect(session('flasher::envelopes'))->not->toBeEmpty();
    });

    test('Validation fails when Password and/or Confirm Password does not match', function () {
        $UserRecord = [
            'first_name' => 'Test',
            'last_name' => 'Client',
            'email' => 'testClient@app.com',
            'password' => 'password',
            'password_confirmation' => 'wrong-password',
            'status' => 'active',
        ];

        actingAs($this->tanent)
            ->post(route('clients.store'), $UserRecord);

        expect(session('flasher::envelopes'))->not->toBeEmpty();
    });

    test('Validation fails when User already exists with same email', function () {
        User::factory()->create([
            'email' => 'testClient@app.com',
        ]);

        $userRecord = [
            'first_name' => 'Test',
            'last_name' => 'Client',
            'email' => 'testClient@app.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'status' => 'active',
        ];
        actingAs($this->tanent)
            ->post(route('clients.store'), $userRecord);

        expect(session('flasher::envelopes'))->not->toBeEmpty();
    });
});

describe('Client Management - Tanent', function () {

    test('Tanent can Access List of Clients', function () {
        actingAs($this->tanent)
            ->get(route('clients.index'))
            ->assertSee('Clients')
            ->assertStatus(200);
    });

    test('Tanent can See Only his Own Clients and cannot See Clients of Other Tanents', function () {
        $otherTanent = User::factory()->Tanent()->create();
        $clients = User::factory(2)->Client($this->tanent->tanent->id)->create();
        $otherTanentClients = User::factory(2)->Client($otherTanent->tanent->id)->create();

        actingAs($this->tanent)
            ->get(route('clients.index'))
            ->assertSee($clients[0]->first_name)
            ->assertSee($clients[1]->first_name)
            ->assertDontSee($otherTanentClients[0]->first_name)
            ->assertDontSee($otherTanentClients[1]->first_name)
            ->assertStatus(200);
    });

    test('Tanent can Access Client Creation Page', function () {
        actingAs($this->tanent)
            ->get(route('clients.create'))
            ->assertSee('Create Client Account')
            ->assertStatus(200);
    });

    test('Tanent can Create New Client(s)', function () {
        $UserRecord = [
            'first_name' => 'Test',
            'last_name' => 'Client',
            'email' => 'testClient@app.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'address' => 'Test Address',
            'contact' => '1234567890',
            'status' => 'active',
            'image' => UploadedFile::fake()->image('avatar.jpg'),
        ];

        actingAs($this->tanent)
            ->post(route('clients.store'), $UserRecord)
            ->assertRedirect(route('clients.index'));

        $NewUser = User::where('email', $UserRecord['email'])->first();
        expect($NewUser)->not->toBeNull();
        expect(Hash::check('password', $NewUser->password))->toBeTrue();

        $NewClient = Client::where('tanent_id', $this->tanent->tanent->id)
            ->where('user_id', $NewUser->id)->first();
        expect($NewClient)->not->toBeNull();

        $this->assertDatabaseHas('clients', [
            'user_id' => $NewUser->id,
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'testClient@app.com'
        ]);
    });

    test('Tanent can Access Edit Expert Page', function () {
        $client = User::factory()->Client($this->tanent->tanent->id)->create();
        actingAs($this->tanent)
            ->get(route('clients.edit', $client->client->id))
            ->assertSee('Edit Client Account')
            ->assertStatus(200);
    });

    test('Tanent can Edit his Client', function () {
        $client = User::factory()->Client($this->tanent->tanent->id)->create();
        $updatedData = [
            'first_name' => 'UpdatedClient',
            'email' => 'UpdatedClient@app.com',
            'password' => 'UpdatedPassword',
            'password_confirmation' => 'UpdatedPassword',
            'address' => 'UpdatedAddress',
            'contact' => 'UpdatedContact',
            'status' => 'active',
        ];

        actingAs($this->tanent)
            ->put(route('clients.update', $client->id), $updatedData)
            ->assertRedirect(route('clients.index'));

        $this->assertDatabaseHas('users', [
            'id' => $client->id,
            'first_name' => 'UpdatedClient',
            'email' => 'UpdatedClient@app.com',
        ]);
    });

    test('Tanent can Delete his Expert', function () {
        $client = User::factory()->Client($this->tanent->tanent->id)->create();
        actingAs($this->tanent)
            ->delete(route('clients.delete', $client->client->id))
            ->assertRedirect(route('clients.index'));

        $this->assertDatabaseMissing('users', [
            'id' => $client->id,
        ]);

        $this->assertDatabaseMissing('clients', [
            'user_id' => $client->id,
        ]);
    });
});

describe('Client Management - Super Admin', function () {

    test('Super Admin can not See Client', function () {
        actingAs($this->superAdmin)
            ->get(route('clients.index'))
            ->assertStatus(403);
    });

    test('Super Admin can not Create Client', function () {
        actingAs($this->superAdmin)
            ->get(route('clients.create'))
            ->assertStatus(403);
    });

    test('Super Admin can not Edit Client', function () {
        actingAs($this->superAdmin)
            ->get(route('clients.edit', 1))
            ->assertStatus(403);
    });

    test('Super Admin can not Delete Client', function () {
        actingAs($this->superAdmin)
            ->delete(route('clients.delete', 1))
            ->assertStatus(403);
    });
});

describe('Client Management - Expert', function () {

    test('Expert can not See Client', function () {
        $expert = User::factory()->Expert($this->tanent->tanent->id)->create();
        actingAs($expert)
            ->get(route('clients.index'))
            ->assertStatus(403);
    });

    test('Expert can not Create Client', function () {
        $expert = User::factory()->Expert($this->tanent->tanent->id)->create();
        actingAs($expert)
            ->get(route('clients.create'))
            ->assertStatus(403);
    });

    test('Expert can not Edit Client', function () {
        $expert = User::factory()->Expert($this->tanent->tanent->id)->create();
        actingAs($expert)
            ->get(route('clients.edit', 1))
            ->assertStatus(403);
    });

    test('Expert can not Delete Client', function () {
        $expert = User::factory()->Expert($this->tanent->tanent->id)->create();
        actingAs($expert)
            ->delete(route('clients.delete', 1))
            ->assertStatus(403);
    });
});

describe('Client Management - Client', function () {

    test('Client can not See Client', function () {
        $client = User::factory()->Client($this->tanent->tanent->id)->create();
        actingAs($client)
            ->get(route('clients.index'))
            ->assertStatus(403);
    });

    test('Client can not Create Client', function () {
        $client = User::factory()->Client($this->tanent->tanent->id)->create();
        actingAs($client)
            ->get(route('clients.create'))
            ->assertStatus(403);
    });

    test('Client can not Edit Client', function () {
        $client = User::factory()->Client($this->tanent->tanent->id)->create();
        actingAs($client)
            ->get(route('clients.edit', 1))
            ->assertStatus(403);
    });

    test('Client can not Delete Client', function () {
        $client = User::factory()->Client($this->tanent->tanent->id)->create();
        actingAs($client)
            ->delete(route('clients.delete', 1))
            ->assertStatus(403);
    });
});