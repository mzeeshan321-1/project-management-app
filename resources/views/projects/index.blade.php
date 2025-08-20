@extends('layouts.app')

@section('title')
    <title>Projects</title>
@endsection

@section('content')
    <div class="pagetitle">
        <h1>Projects</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Projects</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row mb-4">
            <!-- Statistics Cards -->
            <!-- Total Projects Card - Visible to all roles -->
            <div id="projects-carousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <div class="row">
                            <div class="col-xxl-3 col-md-4">
                                <div class="card info-card sales-card">
                                    <div class="card-body">
                                        <h5 class="card-title">Total Projects</h5>
                                        <div class="d-flex align-items-center">
                                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                                <i class="bi bi-folder"></i>
                                            </div>
                                            <div class="ps-3">
                                                <h6>{{ $statistics['total_projects'] }}</h6>
                                                <span class="text-muted small pt-2 ps-1">Total projects</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-3 col-md-4">
                                <div class="card info-card revenue-card">
                                    <div class="card-body">
                                        <h5 class="card-title">Completed Projects</h5>
                                        <div class="d-flex align-items-center">
                                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                                <i class="bi bi-check-circle"></i>
                                            </div>
                                            <div class="ps-3">
                                                <h6>{{ $statistics['completed_projects'] }}</h6>
                                                <span class="text-success small pt-1 fw-bold">
                                                    {{ $statistics['total_projects'] > 0 ? round(($statistics['completed_projects'] / $statistics['total_projects']) * 100, 1) : 0 }}%
                                                </span>
                                                <span class="text-muted small pt-2 ps-1">completion rate</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-3 col-md-4">
                                <div class="card info-card customers-card">
                                    <div class="card-body">
                                        <h5 class="card-title">In Progress</h5>
                                        <div class="d-flex align-items-center">
                                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                                <i class="bi bi-clock"></i>
                                            </div>
                                            <div class="ps-3">
                                                <h6>{{ $statistics['in_progress_projects'] }}</h6>
                                                <span class="text-info small pt-2 ps-1">active projects</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="row">
                            @if (auth()->user()->hasRole(['middleman', 'super-admin']))
                                <div class="col-xxl-3 col-md-4">
                                    <div class="card info-card sales-card">
                                        <div class="card-body">
                                            <h5 class="card-title">Total Budget</h5>
                                            <div class="d-flex align-items-center">
                                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                                    <i class="bi bi-currency-dollar"></i>
                                                </div>
                                                <div class="ps-3">
                                                    <h6>${{ number_format($statistics['total_budget']) }}</h6>
                                                    <span class="text-success small pt-1 fw-bold">
                                                        ${{ number_format($statistics['completed_budget']) }}
                                                    </span>
                                                    <span class="text-muted small pt-2 ps-1">completed</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if (auth()->user()->hasRole(['middleman', 'super-admin', 'expert']))
                                <div class="col-xxl-3 col-md-4">
                                    <div class="card info-card revenue-card">
                                        <div class="card-body">
                                            <h5 class="card-title">Overdue Projects</h5>
                                            <div class="d-flex align-items-center">
                                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                                    <i class="bi bi-exclamation-triangle"></i>
                                                </div>
                                                <div class="ps-3">
                                                    <h6 class="{{ $statistics['overdue_projects'] > 0 ? 'text-danger' : '' }}">
                                                        {{ $statistics['overdue_projects'] }}
                                                    </h6>
                                                    <span class="text-muted small pt-2 ps-1">past deadline</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if (auth()->user()->hasRole(['middleman', 'super-admin']))
                                <div class="col-xxl-3 col-md-4">
                                    <div class="card info-card customers-card">
                                        <div class="card-body">
                                            <h5 class="card-title">Cancelled Projects</h5>
                                            <div class="d-flex align-items-center">
                                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                                    <i class="bi bi-x-circle"></i>
                                                </div>
                                                <div class="ps-3">
                                                    <h6 class="{{ $statistics['cancelled_projects'] > 0 ? 'text-danger' : '' }}">
                                                        {{ $statistics['cancelled_projects'] }}
                                                    </h6>
                                                    <span class="text-muted small pt-2 ps-1">cancelled</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#projects-carousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon text-secondary" aria-hidden="false"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#projects-carousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon text-secondary" aria-hidden="false"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>

            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title">Projects Detailed List</h5>
                            @can('manage projects')
                                <div class="col text-end" title="Create Projects">
                                    <a href="{{ route('projects.create') }}" class="btn btn-primary">
                                        <i class="ri-add-fill"></i> Create Projects
                                    </a>
                                </div>
                            @endcan
                        </div>
                        <!-- Table with centered content -->
                        <div class="table-responsive">
                            <table class="table datatable text-nowrap">
                                <thead>
                                    <tr class="text-center">
                                        <th class="text-center align-middle">P.ID</th>
                                        @role('middleman')
                                            <th class="text-center align-middle">Client</th>
                                            @elserole('client')
                                            <th class="text-center align-middle">Tanent</th>
                                        @endrole
                                        <th class="align-middle">Title</th>
                                        <th class="text-center align-middle">Description</th>
                                        <th class="text-center align-middle">Start Date</th>
                                        <th class="text-center align-middle">Deadline</th>
                                        <th class="text-center align-middle">Budget</th>
                                        <th class="text-center align-middle">Status</th>
                                        <th class="text-center align-middle">Action</th>
                                    </tr>
                                </thead>
                                @if ($projects->isNotEmpty())
                                    <tbody>
                                        @foreach ($projects as $project)
                                            <tr>
                                                <td class="text-center align-middle">{{ $project->id }}</td>
                                                @role('middleman')
                                                    <td class="text-center align-middle">
                                                        {{ $project->client->user->first_name }}
                                                        {{ $project->client->user->last_name }}</td>
                                                    @elserole('client')
                                                    <td class="text-center align-middle">
                                                        {{ $project->tanent->user->first_name }}
                                                        {{ $project->tanent->user->last_name }}</td>
                                                @endrole
                                                <td class="align-middle">{{ $project->title }}</td>
                                                <td class="text-center align-middle">{{ $project->description ?? 'N/A' }}
                                                </td>
                                                <td class="text-center align-middle">
                                                    {{ $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('d-M-Y') : 'N/A' }}
                                                </td>
                                                <td class="text-center align-middle">
                                                    {{ $project->deadline ? \Carbon\Carbon::parse($project->deadline)->format('d-M-Y') : 'N/A' }}
                                                </td>
                                                @role(['middleman', 'client'])
                                                    <td class="text-center align-middle">{{ $project->budget ?? 'N/A' }}</td>
                                                    @elserole('expert')
                                                    <td class="text-center align-middle">
                                                        {{ $project->projectAssigns->sum('budget') ?? 'N/A' }}</td>
                                                @endrole
                                                <td class="text-center align-middle">
                                                    @if ($project->status == 'in_progress')
                                                        <span
                                                            class="badge bg-info">{{ strtoupper($project->status) }}</span>
                                                    @elseif ($project->status == 'completed')
                                                        <span
                                                            class="badge bg-success">{{ strtoupper($project->status) }}</span>
                                                    @elseif ($project->status == 'inactive')
                                                        <span
                                                            class="badge bg-secondary">{{ strtoupper($project->status) }}</span>
                                                    @elseif ($project->status == 'cancelled')
                                                        <span
                                                            class="badge bg-danger">{{ strtoupper($project->status) }}</span>
                                                    @endif
                                                </td>
                                                <td class="text-center align-middle">
                                                    <div class="dropdown position-static">
                                                        <button class="btn btn-light btn-sm rounded-circle" type="button"
                                                            data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="bi bi-three-dots-vertical"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end"
                                                            style="position: fixed;">
                                                            <li>
                                                                <a href="{{ route('projects.show', $project->id) }}"
                                                                    class="dropdown-item">
                                                                    <i class="ri-eye-line"></i> Show
                                                                </a>
                                                            </li>
                                                            @can('manage projects')
                                                                <li>
                                                                    <a class="dropdown-item"
                                                                        href="{{ route('projects.edit', $project->id) }}">
                                                                        <i class="bi bi-pencil me-2"></i> Edit
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <form
                                                                        action="{{ route('projects.delete', $project->id) }}"
                                                                        method="POST">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit"
                                                                            class="dropdown-item text-danger"
                                                                            onclick="return confirm('Are you sure you want to delete this file?')">
                                                                            <i class="bi bi-trash me-2"></i> Delete
                                                                        </button>
                                                                    </form>
                                                                </li>
                                                            @endcan
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                @endif
                            </table>
                        </div>
                        <!-- End Table with centered content -->
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
