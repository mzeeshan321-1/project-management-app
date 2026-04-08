<?php

use App\Models\file;
use App\Models\User;
use App\Models\Project;
use Illuminate\Http\UploadedFile;
use function Pest\Laravel\actingAs;

beforeEach(function () {
    $this->superAdmin = User::factory()->SuperAdmin()->create();
    $this->tanent = User::factory()->Tanent()->create();
    $this->client = User::factory()->Client($this->tanent->tanent->id)->create();
    $this->expert = User::factory()->Expert($this->tanent->tanent->id)->create();
    $this->project = Project::factory()->create([
        'tanent_id' => $this->tanent->tanent->id,
        'client_id' => $this->client->client->id,
    ]);
    $this->file = file::factory()->create([
        'project_id' => $this->project->id,
        'tanent_id' => $this->tanent->tanent->id,
        'uploaded_by' => $this->expert->expert->id,
    ]);
});

describe('Document', function () {

    describe('Validation', function () {

        test('Validation fails when required fields are empty', function () {
            $file = [
                'project_id' => '',
                'tanent_id' => '',
                'uploaded_by' => '',
                'file_name' => '',
                'file_type' => '',
                'file_url' => '',
                'description' => '',
            ];
            actingAs($this->tanent)
                ->post(route('files.store'), $file)
                ->assertRedirectBack();

            expect(session('flasher::envelopes'))->not->toBeEmpty();
        });

        test('Validation fails when file is not selected', function () {
            $file = [
                'project_id' => $this->project->id,
                'tanent_id' => $this->tanent->tanent->id,
                'uploaded_by' => $this->expert->expert->id,
                'file_name' => 'Test File',
                'file_type' => 'document',
                'file_url' => '',
            ];

            actingAs($this->tanent)
                ->post(route('files.store'), $file)
                ->assertRedirectBack();

            expect(session('flasher::envelopes'))->not->toBeEmpty();
        });
    });

    describe('Super Admin', function () {

        test('Super Admin can not View Uploaded Files', function () {
            actingAs($this->superAdmin)
                ->get(route('files.index'))
                ->assertStatus(403);
        });

        test('Super Admin can not Upload Files', function () {
            actingAs($this->superAdmin)
                ->get(route('files.create'))
                ->assertStatus(403);
        });

        test('Super Admin can not Edit Uploaded Files', function () {
            actingAs($this->superAdmin)
                ->get(route('files.edit', $this->file->id))
                ->assertStatus(403);
        });

        test('Super Admin can not Delete Uploaded Files', function () {
            actingAs($this->superAdmin)
                ->delete(route('files.delete', $this->file->id))
                ->assertStatus(403);
        });
    });

    describe('Tenant', function () {

        test('Tenant can View Uploaded Files', function () {
            actingAs($this->tanent)
                ->get(route('files.index'))
                ->assertStatus(200);
        });

        test('Tenant can Access Upload Files Page', function () {
            actingAs($this->tanent)
                ->get(route('files.create'))
                ->assertStatus(200);
        });

        test('Tenant can Upload Files', function () {
            $file = [
                'project_id' => $this->project->id,
                'tanent_id' => $this->tanent->tanent->id,
                'uploaded_by' => $this->tanent->id,
                'file_name' => 'New Test File',
                'file_type' => 'document',
                'image' => UploadedFile::fake()->create('test.pdf', 100),
            ];

            actingAs($this->tanent)
                ->post(route('files.store'), $file)
                ->assertRedirect(route('files.index'));

            $this->assertDatabaseHas('files', [
                'project_id' => $this->project->id,
                'tanent_id' => $this->tanent->tanent->id,
                'uploaded_by' => $this->tanent->id,
                'file_type' => 'document',
            ]);
        });

        test('Tenant can Access Edit Uploaded Files Page', function () {
            actingAs($this->tanent)
                ->get(route('files.edit', $this->file->id))
                ->assertStatus(200);
        });

        test('Tenant can Update Uploaded Files', function () {
            $file = [
                'project_id' => $this->project->id,
                'tanent_id' => $this->tanent->tanent->id,
                'uploaded_by' => $this->expert->expert->id,
                'file_name' => 'Updated Test File',
                'file_type' => 'document',
                'file_url' => $this->file->file_url,
            ];

            actingAs($this->tanent)
                ->put(route('files.update', $this->file->id), $file)
                ->assertRedirect(route('files.index'));
        });

        test('Tenant can Delete Uploaded Files', function () {
            actingAs($this->tanent)
                ->delete(route('files.delete', $this->file->id))
                ->assertStatus(302);
        });
    });

    describe('Expert', function () {

        test('Expert can View Uploaded Files', function () {
            actingAs($this->expert)
                ->get(route('files.index'))
                ->assertStatus(200);
        });

        test('Expert can Access Upload Files Page', function () {
            actingAs($this->expert)
                ->get(route('files.create'))
                ->assertStatus(200);
        });

        test('Expert can Upload Files', function () {
            $file = [
                'project_id' => $this->project->id,
                'tanent_id' => $this->tanent->tanent->id,
                'uploaded_by' => $this->expert->id,
                'file_name' => 'New Test File',
                'file_type' => 'document',
                'image' => UploadedFile::fake()->create('test.pdf', 100),
            ];

            actingAs($this->expert)
                ->post(route('files.store'), $file)
                ->assertRedirect(route('files.index'));

            $this->assertDatabaseHas('files', [
                'project_id' => $this->project->id,
                'tanent_id' => $this->tanent->tanent->id,
                'uploaded_by' => $this->expert->id,
                'file_type' => 'document',
            ]);
        });

        test('Expert can Access Edit Uploaded Files Page', function () {
            actingAs($this->expert)
                ->get(route('files.edit', $this->file->id))
                ->assertStatus(200);
        });

        test('Expert can Update Uploaded Files', function () {
            $file = [
                'project_id' => $this->project->id,
                'tanent_id' => $this->tanent->tanent->id,
                'uploaded_by' => $this->expert->expert->id,
                'file_name' => 'Updated Test File',
                'file_type' => 'document',
                'file_url' => $this->file->file_url,
            ];

            actingAs($this->expert)
                ->put(route('files.update', $this->file->id), $file)
                ->assertRedirect(route('files.index'));
        });

        test('Expert can Delete Uploaded Files', function () {
            actingAs($this->expert)
                ->delete(route('files.delete', $this->file->id))
                ->assertStatus(302);
        });
    });

    describe('Client', function () {
        test('Client can not View Uploaded Files', function () {
            actingAs($this->client)
                ->get(route('files.index'))
                ->assertStatus(403);
        });

        test('Client can not Upload Files', function () {
            actingAs($this->client)
                ->get(route('files.create'))
                ->assertStatus(403);
        });

        test('Client can not Edit Uploaded Files', function () {
            actingAs($this->client)
                ->get(route('files.edit', $this->file->id))
                ->assertStatus(403);
        });

        test('Client can not Delete Uploaded Files', function () {
            actingAs($this->client)
                ->delete(route('files.delete', $this->file->id))
                ->assertStatus(403);
        });
    });
});

