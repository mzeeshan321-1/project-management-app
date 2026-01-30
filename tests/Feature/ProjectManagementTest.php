<?php

use App\Models\Project;
use App\Models\User;
use function Pest\Laravel\actingAs;

beforeEach(function () {
    $this->superAdmin = User::factory()->SuperAdmin()->create();
    $this->tanent = User::factory()->Tanent()->create();
    $this->client = User::factory()->Client($this->tanent->tanent->id)->create();
    $this->expert = User::factory()->Expert($this->tanent->tanent->id)->create();
});

describe('Project Management', function () {
    describe('Project - Validation', function () {

        test('Validation fails when Required fields are Empty', function () {
            $ProjectRecord = [
                'title' => '',
                'description' => '',
                'start_date' => '',
                'deadline' => '',
                'budget' => '',
                'status' => '',
            ];

            actingAs($this->tanent)
                ->post(route('projects.store'), $ProjectRecord);

            expect(session('flasher::envelopes'))->not->toBeEmpty();
        });
    });

    describe('Project - Super Admin', function () {

        test('Super Admin can not See Project', function () {
            actingAs($this->superAdmin)
                ->get(route('projects.index'))
                ->assertStatus(403);
        });

        test('Super Admin can not Create Project', function () {
            actingAs($this->superAdmin)
                ->get(route('projects.store'))
                ->assertStatus(403);
        });

        test('Super Admin can not Edit Project', function () {
            $project = Project::factory()->create([
                'tanent_id' => $this->tanent->tanent->id,
            ]);
            actingAs($this->superAdmin)
                ->get(route('projects.edit', $project->id))
                ->assertStatus(403);
        });

        test('Super Admin can not Delete Project', function () {
            $project = Project::factory()->create([
                'tanent_id' => $this->tanent->tanent->id,
            ]);
            actingAs($this->superAdmin)
                ->delete(route('projects.delete', $project->id))
                ->assertStatus(403);
        });
    });

    describe('Project - Tanent', function () {

        test('Tanent can Access List of All Projects Related to all of his Clients', function () {
            $otherClient = User::factory()->Client($this->tanent->tanent->id)->create();
            $project = Project::factory()->create([
                'tanent_id' => $this->tanent->tanent->id,
                'client_id' => $otherClient->client->id,
            ]);
            $otherProject = Project::factory()->create([
                'tanent_id' => $this->tanent->tanent->id,
                'client_id' => $this->client->client->id,
            ]);
            actingAs($this->tanent)
                ->get(route('projects.index'))
                ->assertSee('Projects')
                ->assertSee($project->title)
                ->assertSee($otherProject->title)
                ->assertStatus(200);
        });

        test('Tanent can Access Project Creation page', function () {
            actingAs($this->tanent)
                ->get(route('projects.create'))
                ->assertSee('Create Project')
                ->assertStatus(200);
        });

        test('Tanent can Create New Project', function () {
            $project = [
                'client_id' => $this->client->client->id,
                'title' => 'New Project',
                'description' => 'Project Description',
                'start_date' => '01-Jan-2024',
                'deadline' => '31-Dec-2024',
                'budget' => 1000,
                'status' => 'inactive',
            ];
            actingAs($this->tanent)
                ->post(route('projects.store'), $project)
                ->assertRedirect(route('projects.index'));

            $this->assertDatabaseHas('projects', [
                'title' => $project['title'],
            ]);
        });

        test('Tanent can Access Project Edit Page', function () {
            $project = Project::factory()->create([
                'tanent_id' => $this->tanent->tanent->id,
                'client_id' => $this->client->client->id,
            ]);
            actingAs($this->tanent)
                ->get(route('projects.edit', $project->id))
                ->assertSee('Edit Project')
                ->assertStatus(200);
        });

        test('Tanent can Edit Project', function () {
            $project = Project::factory()->create([
                'tanent_id' => $this->tanent->tanent->id,
                'client_id' => $this->client->client->id,
            ]);
            $updatedProject = [
                'client_id' => $this->client->client->id,
                'title' => 'Project Updated',
                'description' => 'Project Description',
                'start_date' => '01-Jan-2024',
                'deadline' => '31-Dec-2024',
                'budget' => 1000,
                'status' => 'inactive',
            ];
            actingAs($this->tanent)
                ->put(route('projects.update', $project->id), $updatedProject)
                ->assertRedirect(route('projects.index'));

            $this->assertDatabaseHas('projects', [
                'title' => 'Project Updated',
            ]);
        });

        test('Tanent can Delete Project', function () {
            $project = Project::factory()->create([
                'tanent_id' => $this->tanent->tanent->id,
            ]);
            actingAs($this->tanent)
                ->delete(route('projects.delete', $project->id))
                ->assertRedirect(route('projects.index'));

            $this->assertDatabaseMissing('projects', [
                'id' => $project->id,
            ]);
        });

        test('Tanent can View Project Details and can Change the Status of a Project', function () {
            $project = Project::factory()->create([
                'tanent_id' => $this->tanent->tanent->id,
                'client_id' => $this->client->client->id,
            ]);
            actingAs($this->tanent)
                ->get(route('projects.show', $project->id))
                ->assertSee('Project Details')
                ->assertStatus(200);
        });

        test('Tanent can Change the Status of a Project', function () {
            $project = Project::factory()->create([
                'tanent_id' => $this->tanent->tanent->id,
                'status' => 'inactive',
            ]);
            actingAs($this->tanent)
                ->patch(route('projects.updateStatus', $project->id), [
                    'status' => 'completed',
                ])
                ->assertRedirect();

            $this->assertDatabaseHas('projects', [
                'id' => $project->id,
                'status' => 'completed',
            ]);
        });
    });

    describe('Project - Expert', function () {

        test('Expert can Access List of Projects', function () {
            actingAs($this->expert)
                ->get(route('projects.index'))
                ->assertSee('Projects')
                ->assertStatus(200);
        });

        test('Expert can See Project Details', function () {
            $project = Project::factory()->create([
                'tanent_id' => $this->tanent->tanent->id,
            ]);
            actingAs($this->expert)
                ->get(route('projects.show', $project->id))
                ->assertSee('Project Details')
                ->assertStatus(200);
        });

        test('Expert cannot Create New Project', function () {
            actingAs($this->expert)
                ->get(route('projects.create'))
                ->assertStatus(403);
        });

        test('Expert cannot Edit Project', function () {
            $project = Project::factory()->create([
                'tanent_id' => $this->tanent->tanent->id,
            ]);
            actingAs($this->expert)
                ->get(route('projects.edit', $project->id))
                ->assertStatus(403);
        });

        test('Expert cannot Delete Project', function () {
            $project = Project::factory()->create([
                'tanent_id' => $this->tanent->tanent->id,
            ]);
            actingAs($this->expert)
                ->delete(route('projects.delete', $project->id))
                ->assertStatus(403);
        });
    });

    describe('Project - Client', function () {

        test('Client can Access List of Projects and can See Projects Related to Him', function () {
            $otherClient = User::factory()->Client($this->tanent->tanent->id)->create();
            $project = Project::factory()->create([
                'tanent_id' => $this->tanent->tanent->id,
                'client_id' => $this->client->client->id,
            ]);
            actingAs($this->client)
                ->get(route('projects.index'))
                ->assertStatus(200)
                ->assertSee($project->title);

            $this->assertDatabaseHas('projects', [
                'client_id' => $this->client->client->id,
            ]);
        });

        test('Client can See Details of His Project', function () {
            $project = Project::factory()->create([
                'tanent_id' => $this->tanent->tanent->id,
                'client_id' => $this->client->client->id,
            ]);
            actingAs($this->client)
                ->get(route('projects.show', $project->id))
                ->assertStatus(200);

            $this->assertDatabaseHas('projects', [
                'client_id' => $this->client->client->id,
            ]);
        });

        test('Client cannot Create New Project', function () {
            actingAs($this->client)
                ->get(route('projects.create'))
                ->assertStatus(403);
        });

        test('Client cannot Edit Project', function () {
            $project = Project::factory()->create([
                'tanent_id' => $this->tanent->tanent->id,
            ]);
            actingAs($this->client)
                ->get(route('projects.edit', $project->id))
                ->assertStatus(403);
        });

        test('Client cannot Delete Project', function () {
            $project = Project::factory()->create([
                'tanent_id' => $this->tanent->tanent->id,
            ]);
            actingAs($this->client)
                ->delete(route('projects.delete', $project->id))
                ->assertStatus(403);
        });
    });
});