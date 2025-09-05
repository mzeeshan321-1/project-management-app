@extends('layouts.app')

@section('title')
    @role('super-admin')
        <title>Dashboard - Super Admin</title>
    @endrole
    @role('middleman')
        <title>Dashboard - Middleman</title>
    @endrole
    @role('expert')
        <title>Dashboard - Expert</title>
    @endrole
    @role('client')
        <title>Dashboard - Client</title>
    @endrole
@endsection

@section('content')
    <div class="pagetitle">
        <h1 class="mb-4">Dashboard</h1>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="row gy-4">
            <!-- Summary Cards Section -->
            <div class="col-12">
                <div class="row gy-4">
                    <!-- User Management Section -->
                    @canany(['manage experts', 'manage clients', 'manage middleman'])
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    @canany(['manage middleman', 'view middleman'])
                                    <h5 class="card-title">Tenant Management</h5>
                                    @elsecanany(['manage experts', 'manage clients'])
                                    <h5 class="card-title">User Management</h5>
                                    @endcanany
                                    <div class="row gy-4">
                                        <!-- Experts Card -->
                                        @can('manage experts')
                                            <div class="col-xxl-3 col-md-4">
                                                <a class="icon" href="{{ route('experts.index') }}">
                                                    <div class="card info-card sales-card">
                                                        <div class="card-body">
                                                            <h5 class="card-title">Experts</h5>
                                                            <div class="d-flex align-items-center">
                                                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="background-color: #e0f8e9;">
                                                                    <i class="bi bi-person-badge" style="color: #2eca6a;"></i>
                                                                </div>
                                                                <div class="ps-3">
                                                                    <h6>{{ $expertsCount }}</h6>
                                                                    <span class="text-muted small pt-2 ps-1">Manage Experts <i class="bi bi-arrow-right"></i></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        @endcan

                                        <!-- Clients Card -->
                                        @can('manage clients')
                                            <div class="col-xxl-3 col-md-4">
                                                <a class="icon" href="{{ route('clients.index') }}">
                                                    <div class="card info-card revenue-card">
                                                        <div class="card-body">
                                                            <h5 class="card-title">Clients</h5>
                                                            <div class="d-flex align-items-center">
                                                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="background-color: #f6f6fe;">
                                                                    <i class="bi bi-person-lines-fill" style="color: #4154f1;"></i>
                                                                </div>
                                                                <div class="ps-3">
                                                                    <h6>{{ $clientsCount }}</h6>
                                                                    <span class="text-muted small pt-2 ps-1">Manage Clients <i class="bi bi-arrow-right"></i></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        @endcan

                                        @can('manage middleman')
                                            <div class="col-xxl-3 col-md-4">
                                                <a class="icon" href="{{ route('middleman.index') }}">
                                                    <div class="card info-card revenue-card">
                                                        <div class="card-body">
                                                            <h5 class="card-title">Tenants</h5>
                                                            <div class="d-flex align-items-center">
                                                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="background-color: #f6f6fe;">
                                                                    <i class="bi bi-person-lines-fill" style="color: #4154f1;"></i>
                                                                </div>
                                                                <div class="ps-3">
                                                                    <h6>{{ $tanentsCount }}</h6>
                                                                    <span class="text-muted small pt-2 ps-1">Manage Tenants <i class="bi bi-arrow-right"></i></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endcanany

                    <!-- Project Management Section -->
                    @canany(['manage projects', 'view projects', 'request new projects', 'update project status', 'assign projects'])
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Project Management</h5>
                                    <div class="row gy-4">
                                        <!-- Projects Card -->
                                        @canany(['manage projects', 'view projects', 'request new projects', 'update project status'])
                                            <div class="col-xxl-3 col-md-4">
                                                <a class="icon" href="{{ route('projects.index') }}">
                                                    <div class="card info-card projects-card">
                                                        <div class="card-body">
                                                            <h5 class="card-title">Projects</h5>
                                                            <div class="d-flex align-items-center">
                                                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="background-color: #fff3cd;">
                                                                    <i class="bi bi-folder" style="color: #ff9900;"></i>
                                                                </div>
                                                                <div class="ps-3">
                                                                    <h6>{{ $projectsCount }}</h6>
                                                                    <span class="text-muted small pt-2 ps-1">Manage Projects <i class="bi bi-arrow-right"></i></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        @endcanany

                                        <!-- Project Assignments Card -->
                                        @can('assign projects')
                                            <div class="col-xxl-3 col-md-4">
                                                <a class="icon" href="{{ route('project_assignments.index') }}">
                                                    <div class="card info-card assignments-card">
                                                        <div class="card-body">
                                                            <h5 class="card-title">Assignments</h5>
                                                            <div class="d-flex align-items-center">
                                                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="background-color: #e0f8ff;">
                                                                    <i class="bi bi-diagram-3" style="color: #00a0e0;"></i>
                                                                </div>
                                                                <div class="ps-3">
                                                                    <h6>{{ $projectAssignmentsCount }}</h6>
                                                                    <span class="text-muted small pt-2 ps-1">Project Assignments <i class="bi bi-arrow-right"></i></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        @endcan

                                        <!-- Tasks Card -->
                                        @canany(['view tasks', 'manage tasks'])
                                            <div class="col-xxl-3 col-md-4">
                                                <a class="icon" href="{{ route('tasks.index') }}">
                                                    <div class="card info-card tasks-card">
                                                        <div class="card-body">
                                                            <h5 class="card-title">Tasks</h5>
                                                            <div class="d-flex align-items-center">
                                                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="background-color: #f0f8ff;">
                                                                    <i class="bi bi-list-task" style="color: #1976d2;"></i>
                                                                </div>
                                                                <div class="ps-3">
                                                                    <h6>{{ $tasksCount }}</h6>
                                                                    <span class="text-muted small pt-2 ps-1">Manage Tasks <i class="bi bi-arrow-right"></i></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        @endcanany
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endcanany

                    <!-- Financial Management Section -->
                    @canany(['view payments', 'manage payments', 'create payments', 'view reports', 'manage reports'])
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Financial Management</h5>
                                    <div class="row gy-4">
                                        <!-- Payments Card -->
                                        @canany(['view payments', 'manage payments', 'create payments'])
                                            <div class="col-xxl-3 col-md-4">
                                                <a class="icon" href="{{ route('payments.index') }}">
                                                    <div class="card info-card payments-card">
                                                        <div class="card-body">
                                                            <h5 class="card-title">Payments</h5>
                                                            <div class="d-flex align-items-center">
                                                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="background-color: #e0fff4;">
                                                                    <i class="bi bi-cash-stack" style="color: #00c853;"></i>
                                                                </div>
                                                                <div class="ps-3">
                                                                    <h6>{{ $paymentsCount }}</h6>
                                                                    <span class="text-muted small pt-2 ps-1">Manage Payments <i class="bi bi-arrow-right"></i></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        @endcanany

                                        <!-- Profit Reports Card -->
                                        @canany(['view reports', 'manage reports'])
                                            <div class="col-xxl-3 col-md-4">
                                                <a class="icon" href="{{ route('profits.index') }}">
                                                    <div class="card info-card reports-card">
                                                        <div class="card-body">
                                                            <h5 class="card-title">Profit Reports</h5>
                                                            <div class="d-flex align-items-center">
                                                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="background-color: #fff0f5;">
                                                                    <i class="bi bi-graph-up" style="color: #e91e63;"></i>
                                                                </div>
                                                                <div class="ps-3">
                                                                    <h6>{{ $profitsCount }}</h6>
                                                                    <span class="text-muted small pt-2 ps-1">View Reports <i class="bi bi-arrow-right"></i></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        @endcanany
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endcanany

                    <!-- File Management Section -->
                    @canany(['upload project deliverables', 'manage project deliverables', 'update project dileverables'])
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">File Management</h5>
                                    <div class="row gy-4">
                                        <!-- Files Card -->
                                        @canany(['upload project deliverables', 'manage project deliverables', 'update project dileverables'])
                                            <div class="col-xxl-3 col-md-4">
                                                <a class="icon" href="{{ route('files.index') }}">
                                                    <div class="card info-card files-card">
                                                        <div class="card-body">
                                                            <h5 class="card-title">Files</h5>
                                                            <div class="d-flex align-items-center">
                                                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="background-color: #f0f0f0;">
                                                                    <i class="bi bi-files" style="color: #607d8b;"></i>
                                                                </div>
                                                                <div class="ps-3">
                                                                    <h6>{{ $filesCount }}</h6>
                                                                    <span class="text-muted small pt-2 ps-1">Manage Files <i class="bi bi-arrow-right"></i></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        @endcanany
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endcanany
                </div>
            </div>

            <!-- Recent Activities Section -->
            <div class="col-12">
                <div class="row gy-4">
                    <!-- Recent Projects -->
                    @canany(['manage projects', 'view projects', 'request new projects', 'update project status'])
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Recent Projects</h5>
                                    <div class="activity">
                                        @forelse($recentProjects as $project)
                                            <div class="activity-item d-flex">
                                                <div class="activite-label">{{ $project->created_at->format('d M') }}</div>
                                                <i class='bi bi-circle-fill activity-badge text-success align-self-start'></i>
                                                <div class="activity-content">
                                                    <a href="{{ route('projects.show', $project) }}" class="fw-bold text-dark">{{ $project->title }}</a>
                                                    <br>
                                                    <small class="text-muted">{{ Str::limit($project->description, 50) }}</small>
                                                </div>
                                            </div>
                                        @empty
                                            <p class="text-muted">No recent projects</p>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endcanany

                    <!-- Pending Tasks -->
                    @canany(['view tasks', 'manage tasks'])
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Pending Tasks</h5>
                                    <div class="activity">
                                        @forelse($pendingTasks as $task)
                                            <div class="activity-item d-flex">
                                                <div class="activite-label">{{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->diffForHumans() : 'No deadline' }}</div>
                                                <i class='bi bi-circle-fill activity-badge text-warning align-self-start'></i>
                                                <div class="activity-content">
                                                    <a href="{{ route('tasks.show', $task) }}" class="fw-bold text-dark">{{ $task->title }}</a>
                                                    <br>
                                                    <small class="text-muted">{{ Str::limit($task->description, 50) }}</small>
                                                </div>
                                            </div>
                                        @empty
                                            <p class="text-muted">No pending tasks</p>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endcanany

                    <!-- Recent Payments -->
                    @canany(['view payments', 'manage payments', 'create payments'])
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Recent Payments</h5>
                                    <div class="activity">
                                        @forelse($recentPayments as $payment)
                                            <div class="activity-item d-flex">
                                                <div class="activite-label">{{ $payment->created_at ? $payment->created_at->diffForHumans() : 'N/A' }}</div>
                                                <i class='bi bi-circle-fill activity-badge text-info align-self-start'></i>
                                                <div class="activity-content">
                                                    Payment for <a href="#" class="fw-bold text-dark">{{ $payment->project->title ?? 'Project' }}</a>
                                                    <br>
                                                    <small class="text-muted">Amount: ${{ number_format($payment->amount, 2) }}</small>
                                                </div>
                                            </div>
                                        @empty
                                            <p class="text-muted">No recent payments</p>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endcanany
                </div>
            </div>
        </div>
    </section>
@endsection
