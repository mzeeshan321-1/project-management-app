<?php

use App\Models\Project;
use App\Models\ProjectAssign;
use App\Models\Task;
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

        test('Validation fails when Client is not Selected', function () {
            $project = [
                'title' => 'New Project',
                'description' => 'Project Description',
                'start_date' => '01-Jan-2024',
                'deadline' => '31-Dec-2024',
                'budget' => 1000,
                'status' => 'inactive',
            ];
            actingAs($this->tanent)
                ->post(route('projects.store'), $project)
                ->assertStatus(302);

            $this->assertDatabaseMissing('projects', [
                'title' => 'New Project',
            ]);

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

        test('Super Admin can not View Project Details', function () {
            $project = Project::factory()->create([
                'tanent_id' => $this->tanent->tanent->id,
                'client_id' => $this->client->client->id,
            ]);
            actingAs($this->superAdmin)
                ->get(route('projects.show', $project->id))
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
                ->assertRedirect(route('projects.index'))
                ->assertStatus(302);

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

        test('Expert can Access List of Projects and can See the List of Assigned Projects', function () {

            $project = Project::factory()->create([
                'tanent_id' => $this->tanent->tanent->id,
                'client_id' => $this->client->client->id,
            ]);
            $ProjectAssign = ProjectAssign::create([
                'tanent_id' => $this->tanent->tanent->id,
                'project_id' => $project->id,
                'expert_id' => $this->expert->expert->id,
                'budget' => 5000,
            ]);
            actingAs($this->expert)
                ->get(route('projects.index'))
                ->assertSee('Projects')
                ->assertStatus(200);

            $this->assertDatabaseHas('projects', [
                'id' => $project->id,
            ]);

            $this->assertDatabaseHas('project_assigns', [
                'id' => $ProjectAssign->id,
                'expert_id' => $this->expert->expert->id,
            ]);
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

    describe('Project Assignment - Validation', function () {

        test('Validation fails when Required fields are Empty', function () {
            $ProjectAssignmentRecord = [
                'project_id' => '',
                'expert_id' => '',
            ];

            actingAs($this->tanent)
                ->post(route('project_assignments.store'), $ProjectAssignmentRecord);

            expect(session('flasher::envelopes'))->not->toBeEmpty();
        });

        test('Validation fails when Project is not Selected', function () {
            $projectAssignment = [
                'expert_id' => $this->expert->id,
            ];
            actingAs($this->tanent)
                ->post(route('project_assignments.store'), $projectAssignment)
                ->assertStatus(302);

            $this->assertDatabaseMissing('project_assigns', [
                'expert_id' => $this->expert->id,
            ]);

            expect(session('flasher::envelopes'))->not->toBeEmpty();
        });
    });

    describe('Project Assignment - Super Admin', function () {

        test('Super Admin cannot Access Project Assignment List', function () {
            actingAs($this->superAdmin)
                ->get(route('project_assignments.index'))
                ->assertStatus(403);
        });

        test('Super Admin cannot Assign Project', function () {
            actingAs($this->superAdmin)
                ->get(route('project_assignments.create'))
                ->assertStatus(403);
        });

        test('Super Admin cannot Edit Assigned Project', function () {
            $project = Project::factory()->create([
                'tanent_id' => $this->tanent->tanent->id,
            ]);
            actingAs($this->superAdmin)
                ->get(route('project_assignments.edit', $project->id))
                ->assertStatus(403);
        });

        test('Super Admin cannot Delete Assigned Project', function () {
            $project = Project::factory()->create([
                'tanent_id' => $this->tanent->tanent->id,
            ]);
            actingAs($this->superAdmin)
                ->delete(route('project_assignments.delete', $project->id))
                ->assertStatus(403);
        });
    });

    describe('Project Assignment - Tanent', function () {

        test('Tanent can Access and See All the List of Assigned Projects', function () {
            $project = Project::factory()->create([
                'tanent_id' => $this->tanent->tanent->id,
                'client_id' => $this->client->client->id,
            ]);
            $ProjectAssign = ProjectAssign::create([
                'tanent_id' => $this->tanent->tanent->id,
                'project_id' => $project->id,
                'expert_id' => $this->expert->expert->id,
                'budget' => 5000,
            ]);
            actingAs($this->tanent)
                ->get(route('project_assignments.index'))
                ->assertSee('Project Assignments')
                ->assertSee($project->title)
                ->assertStatus(200);

            $this->assertDatabaseHas('projects', [
                'id' => $project->id,
            ]);

            $this->assertDatabaseHas('project_assigns', [
                'id' => $ProjectAssign->id,
            ]);
        });

        test('Tanent can Access the Create Page', function () {
            actingAs($this->tanent)
                ->get(route('project_assignments.create'))
                ->assertSee('Create Project Assignment')
                ->assertStatus(200);
        });

        test('Tanent can Assign New Project', function () {
            $project = Project::factory()->create([
                'tanent_id' => $this->tanent->tanent->id,
                'client_id' => $this->client->client->id,
            ]);
            $projectAssignment = [
                'tanent_id' => $this->tanent->tanent->id,
                'project_id' => $project->id,
                'expert_id' => $this->expert->expert->id,
                'budget' => 3000,
            ];
            actingAs($this->tanent)
                ->post(route('project_assignments.store'), $projectAssignment)
                ->assertRedirect(route('project_assignments.index'))
                ->assertStatus(302);

            $this->assertDatabaseHas('project_assigns', [
                'project_id' => $project->id,
                'expert_id' => $this->expert->expert->id,
                'budget' => 3000,
            ]);
        });

        test('Tanent can Access the Edit Page', function () {
            $project = Project::factory()->create([
                'tanent_id' => $this->tanent->tanent->id,
                'client_id' => $this->client->client->id,
            ]);
            $ProjectAssign = ProjectAssign::create([
                'tanent_id' => $this->tanent->tanent->id,
                'project_id' => $project->id,
                'expert_id' => $this->expert->expert->id,
                'budget' => 5000,
            ]);
            actingAs($this->tanent)
                ->get(route('project_assignments.edit', $ProjectAssign->id))
                ->assertSee('Edit Project Assignment')
                ->assertStatus(200);
        });

        test('Tanent can Edit the Assigned Project', function () {
            $project = Project::factory()->create([
                'tanent_id' => $this->tanent->tanent->id,
                'client_id' => $this->client->client->id,
            ]);
            $ProjectAssign = ProjectAssign::create([
                'tanent_id' => $this->tanent->tanent->id,
                'project_id' => $project->id,
                'expert_id' => $this->expert->expert->id,
                'budget' => 5000,
            ]);
            $projectAssignment = [
                'project_id' => $project->id,
                'expert_id' => $this->expert->expert->id,
                'budget' => 3000,
            ];
            actingAs($this->tanent)
                ->put(route('project_assignments.update', $ProjectAssign->id), $projectAssignment)
                ->assertRedirect(route('project_assignments.index'))
                ->assertStatus(302);

            $this->assertDatabaseHas('project_assigns', [
                'id' => $ProjectAssign->id,
                'budget' => 3000,
            ]);
        });

        test('Tanent can Delete the Assigned Project', function () {
            $project = Project::factory()->create([
                'tanent_id' => $this->tanent->tanent->id,
                'client_id' => $this->client->client->id,
            ]);
            $ProjectAssign = ProjectAssign::create([
                'tanent_id' => $this->tanent->tanent->id,
                'project_id' => $project->id,
                'expert_id' => $this->expert->expert->id,
                'budget' => 5000,
            ]);
            actingAs($this->tanent)
                ->delete(route('project_assignments.delete', $ProjectAssign->id))
                ->assertRedirect(route('project_assignments.index'))
                ->assertStatus(302);

            $this->assertDatabaseMissing('project_assigns', [
                'id' => $ProjectAssign->id,
            ]);
        });
    });

    describe('Project Assignment - Expert', function () {

        test('Expert cannot Access Project Assignment List', function () {
            actingAs($this->expert)
                ->get(route('project_assignments.index'))
                ->assertStatus(403);
        });

        test('Expert cannot Assign New Project', function () {
            actingAs($this->expert)
                ->get(route('project_assignments.create'))
                ->assertStatus(403);
        });

        test('Expert cannot Edit the Assigned Project', function () {
            $project = Project::factory()->create([
                'tanent_id' => $this->tanent->tanent->id,
                'client_id' => $this->client->client->id,
            ]);
            $ProjectAssign = ProjectAssign::create([
                'tanent_id' => $this->tanent->tanent->id,
                'project_id' => $project->id,
                'expert_id' => $this->expert->expert->id,
                'budget' => 5000,
            ]);
            actingAs($this->expert)
                ->get(route('project_assignments.edit', $ProjectAssign->id))
                ->assertStatus(403);
        });

        test('Expert cannot Delete the Assigned Project', function () {
            $project = Project::factory()->create([
                'tanent_id' => $this->tanent->tanent->id,
                'client_id' => $this->client->client->id,
            ]);
            $ProjectAssign = ProjectAssign::create([
                'tanent_id' => $this->tanent->tanent->id,
                'project_id' => $project->id,
                'expert_id' => $this->expert->expert->id,
                'budget' => 5000,
            ]);
            actingAs($this->expert)
                ->delete(route('project_assignments.delete', $ProjectAssign->id))
                ->assertStatus(403);

            $this->assertDatabaseHas('project_assigns', [
                'id' => $ProjectAssign->id,
            ]);
        });
    });

    describe('Project Assignment - Client', function () {

        test('Client cannot Access Project Assignment List', function () {
            actingAs($this->client)
                ->get(route('project_assignments.index'))
                ->assertStatus(403);
        });

        test('Client cannot Assign Project', function () {
            actingAs($this->client)
                ->get(route('project_assignments.create'))
                ->assertStatus(403);
        });

        test('Client cannot Edit Assigned Project', function () {
            actingAs($this->client)
                ->get(route('project_assignments.edit', 1))
                ->assertStatus(403);
        });

        test('Client cannot Delete Assigned Project', function () {
            actingAs($this->client)
                ->delete(route('project_assignments.delete', 1))
                ->assertStatus(403);
        });
    });

    describe('Tasks - Validation', function () {

        test('Task Validation Fails When Required Fields Are Empty', function () {
            $taskRecord = [
                'project_id' => '',
                'title' => '',
                'description' => '',
                'due_date' => '',
                'status' => '',
                'priority' => '',
            ];

            actingAs($this->tanent)
                ->post(route('tasks.store'), $taskRecord);

            $envelopes = session('flasher::envelopes');
            expect($envelopes)->not->toBeEmpty();

            $notification = $envelopes[0]->getNotification();

            expect($notification->getType())->toBe('error');
        });

        test('Task Validation Fails When Project is not Selected', function () {
            $taskRecord = [
                'title' => 'New Task',
                'description' => 'Task Description',
                'due_date' => '15-Feb-2026',
                'status' => 'pending',
                'priority' => 'medium',
            ];

            actingAs($this->tanent)
                ->post(route('tasks.store'), $taskRecord)
                ->assertStatus(302);

            $envelopes = session('flasher::envelopes');
            expect($envelopes)->not->toBeEmpty();

            $notification = $envelopes[0]->getNotification();

            expect($notification->getType())->toBe('error');
        });
    });

    describe('Tasks - Super Admin', function () {

        test('Super Admin cannot See Tasks List', function () {
            actingAs($this->superAdmin)
                ->get(route('tasks.index'))
                ->assertStatus(403);
        });

        test('Super Admin cannot See Task Details', function () {
            actingAs($this->superAdmin)
                ->get(route('tasks.show', 1))
                ->assertStatus(403);
        });

        test('Super Admin cannot Create Task', function () {
            actingAs($this->superAdmin)
                ->get(route('tasks.create'))
                ->assertStatus(403);
        });

        test('Super Admin cannot Edit Task', function () {
            actingAs($this->superAdmin)
                ->get(route('tasks.edit', 1))
                ->assertStatus(403);
        });

        test('Super Admin cannot Delete Task', function () {
            actingAs($this->superAdmin)
                ->delete(route('tasks.delete', 1))
                ->assertStatus(403);
        });
    });

    describe('Tasks - Tanent', function () {

        test('Tanent can Access List of Tasks and See List of All Tasks in the Assigned Project', function () {
            $project = Project::factory()->create([
                'tanent_id' => $this->tanent->tanent->id,
                'client_id' => $this->client->client->id,
            ]);
            $task = Task::factory()->create([
                'tanent_id' => $this->tanent->tanent->id,
                'project_id' => $project->id,
            ]);
            actingAs($this->tanent)
                ->get(route('tasks.index'))
                ->assertSee($task->title)
                ->assertStatus(200);

            $this->assertDatabaseHas('tasks', [
                'id' => $task->id,
            ]);
        });

        test('Tanent can View Task Details', function () {
            $project = Project::factory()->create([
                'tanent_id' => $this->tanent->tanent->id,
                'client_id' => $this->client->client->id,
            ]);
            $task = Task::factory()->create([
                'tanent_id' => $this->tanent->tanent->id,
                'project_id' => $project->id,
            ]);
            actingAs($this->tanent)
                ->get(route('tasks.show', $task->id))
                ->assertSee('Task Details')
                ->assertStatus(200);
        });

        test('Tanent can Change Task Status and Task Priority', function () {
            $project = Project::factory()->create([
                'tanent_id' => $this->tanent->tanent->id,
                'client_id' => $this->client->client->id,
            ]);
            $task = Task::factory()->create([
                'tanent_id' => $this->tanent->tanent->id,
                'project_id' => $project->id,
                'status' => 'pending',
                'priority' => 'medium',
            ]);
            actingAs($this->tanent)
                ->patch(route('tasks.updateStatus', $task->id), [
                    'status' => 'completed',
                    'priority' => 'high',
                ])
                ->assertRedirect();

            $this->assertDatabaseHas('tasks', [
                'id' => $task->id,
                'status' => 'completed',
                'priority' => 'high',
            ]);
        });

        test('Tanent can Access Create Task Page', function () {
            actingAs($this->tanent)
                ->get(route('tasks.create'))
                ->assertSee('Create Task')
                ->assertStatus(200);
        });

        test('Tanent can Create Task', function () {
            $project = Project::factory()->create([
                'tanent_id' => $this->tanent->tanent->id,
                'client_id' => $this->client->client->id,
            ]);
            $task = [
                'project_id' => $project->id,
                'tanent_id' => $this->tanent->tanent->id,
                'title' => 'New Task',
                'description' => 'Task Description',
                'due_date' => '15-Feb-2026',
                'status' => 'pending',
                'priority' => 'medium',
            ];
            actingAs($this->tanent)
                ->post(route('tasks.store'), $task)
                ->assertRedirect(route('tasks.index'))
                ->assertStatus(302);

            $this->assertDatabaseHas('tasks', [
                'project_id' => $task['project_id'],
                'tanent_id' => $task['tanent_id'],
                'title' => $task['title'],
                'status' => $task['status'],
                'priority' => $task['priority'],
            ]);
        });

        test('Tanent can Access Edit Task Page', function () {
            $project = Project::factory()->create([
                'tanent_id' => $this->tanent->tanent->id,
                'client_id' => $this->client->client->id,
            ]);
            $task = Task::factory()->create([
                'tanent_id' => $this->tanent->tanent->id,
                'project_id' => $project->id,
            ]);
            actingAs($this->tanent)
                ->get(route('tasks.edit', $task->id))
                ->assertSee('Edit Task')
                ->assertStatus(200);
        });

        test('Tanent can Edit Task', function () {
            $project = Project::factory()->create([
                'tanent_id' => $this->tanent->tanent->id,
                'client_id' => $this->client->client->id,
            ]);
            $task = Task::factory()->create([
                'tanent_id' => $this->tanent->tanent->id,
                'project_id' => $project->id,
                'title' => 'Old Task Name',
                'due_date' => '15-Feb-2026',
                'status' => 'pending',
                'priority' => 'medium',
            ]);
            $UpdatedTask = [
                'project_id' => $project->id,
                'title' => 'New Task Name',
                'status' => 'completed',
                'due_date' => '20-Feb-2026',
                'priority' => 'high',
            ];
            actingAs($this->tanent)
                ->put(route('tasks.update', $task->id), $UpdatedTask)
                ->assertRedirect(route('tasks.index'))
                ->assertStatus(302);

            $this->assertDatabaseHas('tasks', [
                'id' => $task->id,
                'title' => 'New Task Name',
            ]);
        });

        test('Tanent can Delete Task', function () {
            $project = Project::factory()->create([
                'tanent_id' => $this->tanent->tanent->id,
                'client_id' => $this->client->client->id,
            ]);
            $task = Task::factory()->create([
                'tanent_id' => $this->tanent->tanent->id,
                'project_id' => $project->id,
            ]);
            actingAs($this->tanent)
                ->delete(route('tasks.delete', $task->id))
                ->assertStatus(302)
                ->assertRedirect(route('tasks.index'));

            $this->assertDatabaseMissing('tasks', [
                'id' => $task->id,
            ]);
        });
    });

    describe('Tasks - Expert', function () {

        test('Expert can Access List of Tasks and See the List of Tasks in the Assigned Project', function () {
            $project = Project::factory()->create([
                'tanent_id' => $this->tanent->tanent->id,
                'client_id' => $this->client->client->id,
            ]);
            $projectAssign = ProjectAssign::create([
                'tanent_id' => $this->tanent->tanent->id,
                'project_id' => $project->id,
                'expert_id' => $this->expert->expert->id,
                'budget' => 5000,
            ]);
            $task = Task::factory()->create([
                'tanent_id' => $this->tanent->tanent->id,
                'project_id' => $project->id,
            ]);
            actingAs($this->expert)
                ->get(route('tasks.index'))
                ->assertSee('Tasks')
                ->assertSee($task->title)
                ->assertStatus(200);

            $this->assertDatabaseHas('tasks', [
                'id' => $task->id,
                'title' => $task->title,
            ]);
        });

        test('Expert can View their Task Details', function () {
            $project = Project::factory()->create([
                'tanent_id' => $this->tanent->tanent->id,
                'client_id' => $this->client->client->id,
            ]);
            $task = Task::factory()->create([
                'tanent_id' => $this->tanent->tanent->id,
                'project_id' => $project->id,
            ]);
            actingAs($this->expert)
                ->get(route('tasks.show', $task->id))
                ->assertSee('Task Details')
                ->assertStatus(200);
        });

        test('Expert can Update the Status of the Task', function () {
            $project = Project::factory()->create([
                'tanent_id' => $this->tanent->tanent->id,
                'client_id' => $this->client->client->id,
            ]);
            $task = Task::factory()->create([
                'tanent_id' => $this->tanent->tanent->id,
                'project_id' => $project->id,
                'status' => 'pending',
            ]);
            $taskStatusUpdate = [
                'status' => 'completed',
            ];
            actingAs($this->expert)
                ->patch(route('tasks.updateStatus', $task->id), $taskStatusUpdate)
                ->assertStatus(302)
                ->assertRedirect();

            $this->assertDatabaseHas('tasks', [
                'id' => $task->id,
                'status' => 'completed',
            ]);
        });

        test('Expert cannot Create New Task', function () {
            actingAs($this->expert)
                ->get(route('tasks.create'))
                ->assertStatus(403);
        });

        test('Expert cannot Edit Task', function () {
            actingAs($this->expert)
                ->get(route('tasks.edit', 1))
                ->assertStatus(403);
        });

        test('Expert cannot Delete Task', function () {
            actingAs($this->expert)
                ->delete(route('tasks.delete', 1))
                ->assertStatus(403);
        });
    });

    describe('Tasks - Client', function () {

        test('Client cannot See Tasks List', function () {
            actingAs($this->client)
                ->get(route('tasks.index'))
                ->assertStatus(403);
        });

        test('Client cannot View Task Details', function () {
            actingAs($this->client)
                ->get(route('tasks.show', 1))
                ->assertStatus(403);
        });

        test('Client cannot Create Task', function () {
            actingAs($this->client)
                ->get(route('tasks.create'))
                ->assertStatus(403);
        });

        test('Client cannot Edit Task', function () {
            actingAs($this->client)
                ->get(route('tasks.edit', 1))
                ->assertStatus(403);
        });

        test('Client cannot Delete Task', function () {
            actingAs($this->client)
                ->delete(route('tasks.delete', 1))
                ->assertStatus(403);
        });
    });
});