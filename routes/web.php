<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpertsController;
use App\Http\Controllers\ClientsController;
use App\Http\Controllers\TasksController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\ProjectAssignmentController;
use App\Http\Controllers\MiddlemanController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Middleman view Route
    Route::get('/middleman', [MiddlemanController::class, 'index'])->name('middleman.index');
    // Project view Route
    Route::get('/projects', [ProjectsController::class, 'index'])->name('projects.index');
    // task view Route
    Route::get('/tasks', [TasksController::class, 'index'])->name('tasks.index');

    // Super Admin Role Routes
    Route::middleware(['role:super-admin'])->group(function () {

        // Middleman Routes
        Route::get('/middleman/create', [MiddlemanController::class, 'create'])->name('middleman.create');
        Route::post('/middleman', [MiddlemanController::class, 'store'])->name('middleman.store');
        Route::get('/middleman/{id}/edit', [MiddlemanController::class, 'edit'])->name('middleman.edit');
        Route::put('/middleman/{id}', [MiddlemanController::class, 'update'])->name('middleman.update');
        Route::delete('/middleman/{id}', [MiddlemanController::class, 'destroy'])->name('middleman.delete');

    });

    // Middleman Role Routes
    Route::middleware(['role:middleman'])->group(function () {

        // Experts Routes
        Route::get('/experts', [ExpertsController::class, 'index'])->name('experts.index');
        Route::get('/experts/create', [ExpertsController::class, 'create'])->name('experts.create');
        Route::post('/experts', [ExpertsController::class, 'store'])->name('experts.store');
        Route::get('/experts/{id}/edit', [ExpertsController::class, 'edit'])->name('experts.edit');
        Route::put('/experts/{id}', [ExpertsController::class, 'update'])->name('experts.update');
        Route::delete('/experts/{id}', [ExpertsController::class, 'destroy'])->name('experts.delete');

        // Clients Routes
        Route::get('/clients', [ClientsController::class, 'index'])->name('clients.index');
        Route::get('/clients/create', [ClientsController::class, 'create'])->name('clients.create');
        Route::post('/clients', [ClientsController::class, 'store'])->name('clients.store');
        Route::get('/clients/{id}/edit', [ClientsController::class, 'edit'])->name('clients.edit');
        Route::put('/clients/{id}', [ClientsController::class, 'update'])->name('clients.update');
        Route::delete('/clients/{id}', [ClientsController::class, 'destroy'])->name('clients.delete');

        // Projects Routes
        Route::get('/projects/create', [ProjectsController::class, 'create'])->name('projects.create');
        Route::post('/projects', [ProjectsController::class, 'store'])->name('projects.store');
        Route::get('/projects/{id}/edit', [ProjectsController::class, 'edit'])->name('projects.edit');
        Route::put('/projects/{id}', [ProjectsController::class, 'update'])->name('projects.update');
        Route::delete('/projects/{id}', [ProjectsController::class, 'destroy'])->name('projects.delete');

        // Project Assignment Routes
        Route::get('/project-assignments', [ProjectAssignmentController::class, 'index'])->name('project_assignments.index');
        Route::get('/project-assignments/create', [ProjectAssignmentController::class, 'create'])->name('project_assignments.create');
        Route::post('/project-assignments', [ProjectAssignmentController::class, 'store'])->name('project_assignments.store');
        Route::get('/project-assignments/{id}/edit', [ProjectAssignmentController::class, 'edit'])->name('project_assignments.edit');
        Route::put('/project-assignments/{id}', [ProjectAssignmentController::class, 'update'])->name('project_assignments.update');
        Route::delete('/project-assignments/{id}', [ProjectAssignmentController::class, 'destroy'])->name('project_assignments.delete');

        // Task Routes
        Route::get('/tasks/create', [TasksController::class, 'create'])->name('tasks.create');
        Route::post('/tasks', [TasksController::class, 'store'])->name('tasks.store');
        Route::get('/tasks/{id}/edit', [TasksController::class, 'edit'])->name('tasks.edit');
        Route::put('/tasks/{id}', [TasksController::class, 'update'])->name('tasks.update');
        Route::delete('/tasks/{id}', [TasksController::class, 'destroy'])->name('tasks.delete');

    });
});

require __DIR__ . '/auth.php';
