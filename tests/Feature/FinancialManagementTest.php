<?php

use App\Models\User;
use App\Models\Payment;
use App\Models\Project;
use App\Models\Profit;
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
    $this->payment = Payment::factory()->create([
        'project_id' => $this->project->id,
        'sender_id' => $this->client->client->id,
        'reciever_id' => $this->tanent->tanent->id,
    ]);
    $this->profit = Profit::factory()->create([
        'tanent_id' => $this->tanent->tanent->id,
        'project_id' => $this->project->id,
        'payment_id' => $this->payment->id,
    ]);
});

describe('Financial Management', function () {

    describe('Payment', function () {

        describe('Validation', function () {

            test('Validation fails when Required fields are Empty', function () {
                actingAs($this->tanent)
                    ->post(route('payments.store'), []);

                expect(session('flasher::envelopes'))->not->toBeEmpty();
            });

            test('Validation fails when client pays amount not equal to project budget', function () {
                $project = Project::factory()->create([
                    'tanent_id' => $this->tanent->tanent->id,
                    'client_id' => $this->client->client->id,
                    'budget' => 1000,
                    'status' => 'completed',
                ]);
                $payment = [
                    'project_id' => $project->id,
                    'amount' => 500,
                    'sender_id' => $this->client->client->id,
                    'reciever_id' => $this->tanent->tanent->id,
                    'status' => 'pending',
                    'type' => 'debit',
                ];
                actingAs($this->client)
                    ->post(route('payments.store'), $payment);

                expect(session('flasher::envelopes'))->not->toBeEmpty();

                $this->assertDatabaseMissing('payments', [
                    'project_id' => $this->project->id,
                    'amount' => $this->project->budget,
                ]);
            });

            test('Only completed Projects can be paid', function () {
                $project = Project::factory()->make([
                    'tanent_id' => $this->tanent->tanent->id,
                    'client_id' => $this->client->client->id,
                    'budget' => 1000,
                    'status' => 'in_progress',
                ]);
                actingAs($this->tanent)
                    ->post(route('payments.store'), [
                        'project_id' => $project->id,
                        'amount' => $project->budget,
                        'sender_id' => $this->client->client->id,
                        'reciever_id' => $this->tanent->tanent->id,
                        'status' => 'pending',
                        'type' => 'debit',
                    ])
                    ->assertStatus(302);

                $this->assertDatabaseMissing('payments', [
                    'project_id' => $project->id,
                    'amount' => $project->budget,
                ]);
            });
        });

        describe('Super Admin', function () {

            test('Super Admin can not View Payment', function () {
                actingAs($this->superAdmin)
                    ->get(route('payments.index'))
                    ->assertStatus(403);
            });

            test('Super Admin can not Create Payment', function () {
                actingAs($this->superAdmin)
                    ->get(route('payments.create'))
                    ->assertStatus(403);
            });

            test('Super Admin can not Edit Payment', function () {
                actingAs($this->superAdmin)
                    ->get(route('payments.edit', $this->payment->id))
                    ->assertStatus(403);
            });

            test('Super Admin can not View Payment Details', function () {
                actingAs($this->superAdmin)
                    ->get(route('payments.details', $this->payment->id))
                    ->assertStatus(403);
            });

            test('Super Admin can not Delete Payment', function () {
                actingAs($this->superAdmin)
                    ->delete(route('payments.delete', $this->payment->id))
                    ->assertStatus(403);
            });
        });

        describe('Tanent', function () {

            test('Tanent can View their Payment', function () {
                actingAs($this->tanent)
                    ->get(route('payments.index'))
                    ->assertStatus(200);
            });

            test('Tanent can not Proceed to Payment Details', function () {
                actingAs($this->tanent)
                    ->get(route('payments.details', $this->payment->id))
                    ->assertStatus(403);
            });

            test('Tanent can Access Create Payment Page', function () {
                actingAs($this->tanent)
                    ->get(route('payments.create'))
                    ->assertStatus(200);
            });

            test('Tanent can make/create Payment', function () {
                $data = [
                    'project_id' => $this->project->id,
                    'sender_id' => $this->client->client->id,
                    'reciever_id' => $this->tanent->tanent->id,
                    'amount' => $this->project->budget,
                    'status' => 'pending',
                    'type' => 'debit',
                ];

                actingAs($this->client)
                    ->post(route('payments.store'), $data)
                    ->assertRedirect(route('payments.index'))
                    ->assertStatus(302);

                $this->assertDatabaseHas('payments', [
                    'amount' => $data['amount'],
                    'status' => 'pending',
                    'type' => 'debit',
                ]);
            });

            test('Tanent can Access Edit Payment Page', function () {
                actingAs($this->tanent)
                    ->get(route('payments.edit', $this->payment->id))
                    ->assertStatus(200);
            });

            test('Tanent can Edit Payment', function () {
                $updatedData = [
                    'project_id' => $this->project->id,
                    'sender_id' => $this->client->client->id,
                    'reciever_id' => $this->tanent->tanent->id,
                    'amount' => $this->project->budget,
                    'status' => 'pending',
                    'type' => 'debit',
                    'note' => 'Updated note',
                ];

                actingAs($this->client)
                    ->put(route('payments.update', $this->payment->id), $updatedData)
                    ->assertRedirect(route('payments.index'))
                    ->assertStatus(302);

                $this->assertDatabaseHas('payments', [
                    'amount' => $updatedData['amount'],
                    'note' => $updatedData['note'],
                ]);
            });

            test('Tanent can Delete Payment', function () {
                actingAs($this->tanent)
                    ->delete(route('payments.delete', $this->payment->id))
                    ->assertRedirect(route('payments.index'))
                    ->assertStatus(302);
            });
        });

        describe('Expert', function () {

            test('Expert can View their Payment', function () {
                actingAs($this->expert)
                    ->get(route('payments.index'))
                    ->assertStatus(200);
            });

            test('Expert can not Proceed to Payment Details', function () {
                actingAs($this->expert)
                    ->get(route('payments.details', $this->payment->id))
                    ->assertStatus(403);
            });

            test('Expert can not make Payment', function () {
                actingAs($this->expert)
                    ->post(route('payments.store'), [
                        'project_id' => $this->project->id,
                        'amount' => $this->project->budget,
                    ])
                    ->assertStatus(403);
            });

            test('Expert can not Edit Payment', function () {
                actingAs($this->expert)
                    ->get(route('payments.edit', $this->payment->id))
                    ->assertStatus(403);
            });

            test('Expert can not Delete Payment', function () {
                actingAs($this->expert)
                    ->delete(route('payments.delete', $this->payment->id))
                    ->assertStatus(403);
            });
        });

        describe('Client', function () {

            test('Client can View their Payment', function () {
                actingAs($this->client)
                    ->get(route('payments.index'))
                    ->assertStatus(200);
            });

            test('Client can Proceed to Payment Details', function () {
                actingAs($this->client)
                    ->get(route('payments.details', $this->payment->id))
                    ->assertStatus(200);
            });

            test('Client can Access Create Payment Page', function () {
                actingAs($this->client)
                    ->get(route('payments.create'))
                    ->assertStatus(200);
            });

            test('Client can make/create Payment', function () {
                $data = [
                    'project_id' => $this->project->id,
                    'sender_id' => $this->client->client->id,
                    'reciever_id' => $this->tanent->tanent->id,
                    'amount' => $this->project->budget,
                    'status' => 'pending',
                    'type' => 'debit',
                ];

                actingAs($this->client)
                    ->post(route('payments.store'), $data)
                    ->assertRedirect(route('payments.index'))
                    ->assertStatus(302);

                $this->assertDatabaseHas('payments', [
                    'amount' => $data['amount'],
                    'status' => 'pending',
                    'type' => 'debit',
                ]);
            });

            test('Client can Access Edit Payment Page', function () {
                actingAs($this->client)
                    ->get(route('payments.edit', $this->payment->id))
                    ->assertStatus(200);
            });

            test('Client can Edit Payment', function () {
                $updatedData = [
                    'project_id' => $this->project->id,
                    'sender_id' => $this->client->client->id,
                    'reciever_id' => $this->tanent->tanent->id,
                    'amount' => $this->project->budget,
                    'status' => 'pending',
                    'type' => 'debit',
                    'note' => 'Updated note',
                ];

                actingAs($this->client)
                    ->put(route('payments.update', $this->payment->id), $updatedData)
                    ->assertRedirect(route('payments.index'))
                    ->assertStatus(302);

                $this->assertDatabaseHas('payments', [
                    'amount' => $updatedData['amount'],
                    'note' => $updatedData['note'],
                ]);
            });

            test('Client can Delete Payment', function () {
                actingAs($this->client)
                    ->delete(route('payments.delete', $this->payment->id))
                    ->assertRedirect(route('payments.index'))
                    ->assertStatus(302);
            });
        });
    });


    describe('Profit Report', function () {

        describe('Super Admin', function() {

            test('Super Admin can not View Profit Report', function() {
                actingAs($this->superAdmin)
                    ->get(route('profits.index'))
                    ->assertStatus(403);
            });

            test('Super Admin can not View Profit Report Details', function() {
                actingAs($this->superAdmin)
                    ->get(route('profits.show', $this->profit->id))
                    ->assertStatus(403);
            });
        });

        describe('Tanent', function() {

            test('Tanent can View Profit Report', function() {
                actingAs($this->tanent)
                    ->get(route('profits.index'))
                    ->assertStatus(200);
            });

            test('Tanent can View Profit Report Details', function() {
                actingAs($this->tanent)
                    ->get(route('profits.show', $this->profit->id))
                    ->assertStatus(200);
            });
        });

        describe('Expert', function() {

            test('Expert can not View Profit Report', function() {
                actingAs($this->expert)
                    ->get(route('profits.index'))
                    ->assertStatus(403);
            });

            test('Expert can not View Profit Report Details', function() {
                actingAs($this->expert)
                    ->get(route('profits.show', $this->profit->id))
                    ->assertStatus(403);
            });
        });

        describe('Client', function() {

            test('Client can not View Profit Report', function() {
                actingAs($this->client)
                    ->get(route('profits.index'))
                    ->assertStatus(403);
            });

            test('Client can not View Profit Report Details', function() {
                actingAs($this->client)
                    ->get(route('profits.show', $this->profit->id))
                    ->assertStatus(403);
            });
        });
    });
});