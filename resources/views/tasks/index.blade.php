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
                                                <td class="align-middle">{{ $task->project->title }}</td>
                                                <td class="text-center align-middle">{{ $task->title }}
                                                </td>
                                                <td class="text-center align-middle">{{ $task->description ?? 'N/A' }}</td>
                                                <td class="text-center align-middle">
                                                    {{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->diffForHumans() : 'N/A' }}
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
                                                        <span class="badge bg-info">{{ strtoupper($task->status) }}</span>
                                                    @elseif ($task->status == 'completed')
                                                        <span
                                                            class="badge bg-success">{{ strtoupper($task->status) }}</span>
                                                    @elseif ($task->status == 'in_progress')
                                                        <span
                                                            class="badge bg-secondary">{{ strtoupper($task->status) }}</span>
                                                    @elseif ($task->status == 'cancelled')
                                                        <span
                                                            class="badge bg-danger">{{ strtoupper($task->status) }}</span>
                                                    @elseif ($task->status == 'on_hold')
                                                        <span class="badge bg-dark">{{ strtoupper($task->status) }}</span>
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
                                                                        <button type="submit" class="dropdown-item text-danger"
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
