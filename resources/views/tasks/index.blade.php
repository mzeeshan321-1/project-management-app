@extends('layouts.app')

@section('title')
    <title>Tasks</title>
@endsection

@section('content')
    <div class="pagetitle">
        <h1>Tasks</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Tasks</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    {{-- {{ dd($tasks) }} --}}
    <section class="section">
        <div class="row">
            <!-- Statistics Cards -->
            <div id="projects-carousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <div class="row">
                            <div class="col-xxl-3 col-md-4">
                                <div class="card info-card customers-card">
                                    <div class="card-body">
                                        <h5 class="card-title">Overdue Tasks</h5>
                                        <div class="d-flex align-items-center">
                                            <div
                                                class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                                <i class="bi bi-exclamation-triangle"></i>
                                            </div>
                                            <div class="ps-3">
                                                <h6 class="{{ $statistics['overdue_tasks'] > 0 ? 'text-danger' : '' }}">
                                                    {{ $statistics['overdue_tasks'] }}
                                                </h6>
                                                <span class="text-muted small pt-2 ps-1">Past Due Date</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xxl-3 col-md-4">
                                <div class="card info-card revenue-card">
                                    <div class="card-body">
                                        <h5 class="card-title">Completed Tasks</h5>
                                        <div class="d-flex align-items-center">
                                            <div
                                                class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                                <i class="bi bi-check-circle"></i>
                                            </div>
                                            <div class="ps-3">
                                                <h6>{{ $statistics['completed_tasks'] }}</h6>
                                                <span class="text-success small pt-1 fw-bold">
                                                    {{ $statistics['total_projects'] > 0 ? round(($statistics['completed_tasks'] / $statistics['total_projects']) * 100, 1) : 0 }}%
                                                </span>
                                                <span class="text-muted small pt-2 ps-1">Completion Rate</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-3 col-md-4">
                                <div class="card info-card sales-card">
                                    <div class="card-body">
                                        <h5 class="card-title">In Progress</h5>
                                        <div class="d-flex align-items-center">
                                            <div
                                                class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                                <i class="bi bi-clock"></i>
                                            </div>
                                            <div class="ps-3">
                                                <h6>{{ $statistics['in_progress_tasks'] }}</h6>
                                                <span class="text-info small pt-2 ps-1">Active projects</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="row">
                            <div class="col-xxl-3 col-md-4">
                                <div class="card info-card customers-card">
                                    <div class="card-body">
                                        <h5 class="card-title">Pending Tasks</h5>
                                        <div class="d-flex align-items-center">
                                            <div
                                                class="card-icon rounded-circle d-flex align-items-center justify-content-center text-warning">
                                                <i class="bi bi-hourglass-split"></i>
                                            </div>
                                            <div class="ps-3">
                                                <h6 class="{{ $statistics['pending_tasks'] > 0 ? 'text-warning' : '' }}">
                                                    {{ $statistics['pending_tasks'] }}
                                                </h6>
                                                <span class="text-muted small pt-2 ps-1">Pending</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xxl-3 col-md-4">
                                <div class="card info-card revenue-card">
                                    <div class="card-body">
                                        <h5 class="card-title">On Hold Tasks</h5>
                                        <div class="d-flex align-items-center">
                                            <div
                                                class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-light text-secondary">
                                                <i class="bi bi-exclamation-triangle"></i>
                                            </div>
                                            <div class="ps-3">
                                                <h6 class="{{ $statistics['on_hold_tasks'] > 0 ? 'text-secondary' : '' }}">
                                                    {{ $statistics['on_hold_tasks'] }}
                                                </h6>
                                                <span class="text-muted small pt-2 ps-1">On Hold</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xxl-3 col-md-4">
                                <div class="card info-card customers-card">
                                    <div class="card-body">
                                        <h5 class="card-title">Cancelled Tasks</h5>
                                        <div class="d-flex align-items-center">
                                            <div
                                                class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                                <i class="bi bi-x-circle"></i>
                                            </div>
                                            <div class="ps-3">
                                                <h6 class="{{ $statistics['cancelled_tasks'] > 0 ? 'text-danger' : '' }}">
                                                    {{ $statistics['cancelled_tasks'] }}
                                                </h6>
                                                <span class="text-muted small pt-2 ps-1">Cancelled</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="carousel-control-next" type="button"
                    data-bs-target="#projects-carousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon bg-secondary rounded-circle border-0 d-none d-md-block"
                        aria-hidden="false"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>

            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title">Tasks Details</h5>
                            @can('manage tasks')
                                <div class="col text-end" title="Create Tasks">
                                    <a href="{{ route('tasks.create') }}" class="btn btn-primary">
                                        <i class="ri-add-fill"></i> Create Tasks
                                    </a>
                                </div>
                            @endcan
                        </div>
                        <!-- Table with centered content -->
                        <div class="table-responsive">
                            <table class="table datatable text-nowrap">
                                <thead>
                                    <tr class="text-center">
                                        <th class="text-center align-middle">T.ID</th>
                                        <th class="text-center align-middle">Project</th>
                                        <th class="text-center align-middle">Title</th>
                                        <th class="text-center align-middle">Description</th>
                                        <th class="text-center align-middle">Due Date</th>
                                        @if ($tasks->pluck('status') == 'completed')
                                            <th class="text-center align-middle">Completed At</th>
                                        @endif
                                        <th class="text-center align-middle">priority</th>
                                        <th class="text-center align-middle">Status</th>
                                        <th class="text-center align-middle">Action</th>
                                    </tr>
                                </thead>
                                @if ($tasks->isNotEmpty())
                                    <tbody>
                                        @foreach ($tasks as $task)
                                            <tr>
                                                <td class="text-center align-middle">{{ $task->id }}</td>
                                                <td class="align-middle">
                                                    <a class="fw-bold" href="{{ route('projects.index') }}">
                                                        {{ $task->project->title }}
                                                    </a>
                                                </td>
                                                <td class="text-center align-middle">
                                                    <a class="fw-bold" href="{{ route('tasks.show', $task->id) }}">
                                                        {{ $task->title }}
                                                    </a>

                                                </td>
                                                <td class="text-center align-middle">{{ $task->description ?? 'N/A' }}
                                                </td>
                                                <td class="text-center align-middle">
                                                    {{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('d M Y') : 'N/A' }}
                                                </td>
                                                @if ($task->status == 'completed')
                                                    <td class="text-center align-middle">
                                                        {{ $task->completed_at ? \Carbon\Carbon::parse($task->updated_at)->diffForHumans() : 'N/A' }}
                                                    </td>
                                                @endif
                                                <td class="text-center align-middle">
                                                    @if ($task->priority == 'low')
                                                        <span
                                                            class="badge bg-secondary">{{ strtoupper($task->priority) }}</span>
                                                    @elseif ($task->priority == 'medium')
                                                        <span
                                                            class="badge bg-success">{{ strtoupper($task->priority) }}</span>
                                                    @elseif ($task->priority == 'high')
                                                        <span
                                                            class="badge bg-danger">{{ strtoupper($task->priority) }}</span>
                                                    @endif
                                                </td>
                                                <td class="text-center align-middle">
                                                    @if ($task->status == 'pending')
                                                        <span class="badge bg-warning">{{ strtoupper($task->status) }}</span>
                                                    @elseif ($task->status == 'completed')
                                                        <span
                                                            class="badge bg-success">{{ strtoupper($task->status) }}</span>
                                                    @elseif ($task->status == 'in_progress')
                                                        <span
                                                            class="badge bg-info">{{ strtoupper($task->status) }}</span>
                                                    @elseif ($task->status == 'cancelled')
                                                        <span
                                                            class="badge bg-danger">{{ strtoupper($task->status) }}</span>
                                                    @elseif ($task->status == 'on_hold')
                                                        <span class="badge bg-secondary">{{ strtoupper($task->status) }}</span>
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
                                                                <a href="{{ route('tasks.show', $task->id) }}"
                                                                    class="dropdown-item">
                                                                    <i class="ri-eye-line"></i> Show
                                                                </a>
                                                            </li>
                                                            @can('manage tasks')
                                                                <li>
                                                                    <a class="dropdown-item"
                                                                        href="{{ route('tasks.edit', $task->id) }}">
                                                                        <i class="bi bi-pencil me-2"></i> Edit
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <form action="{{ route('tasks.delete', $task->id) }}"
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
