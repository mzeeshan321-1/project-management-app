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
    {{-- {{ dd($projects) }} --}}
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title">Tasks Details</h5>
                            <div class="col text-end" title="Create Tasks">
                                <a href="{{ route('tasks.create') }}" class="btn btn-primary">
                                    <i class="ri-add-fill"></i> Create Tasks
                                </a>
                            </div>
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
                                        <th class="text-center align-middle">Completed At</th>
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
                                                <td class="text-center align-middle">
                                                    {{ $task->completed_at ? \Carbon\Carbon::parse($task->completed_at)->diffForHumans() : 'N/A' }}
                                                </td>
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
                                                        <span
                                                            class="badge bg-info">{{ strtoupper($task->status) }}</span>
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
                                                        <span
                                                            class="badge bg-dark">{{ strtoupper($task->status) }}</span>
                                                    @endif
                                                </td>
                                                <td class="text-center align-middle">
                                                    <div class="d-flex justify-content-center">
                                                        <a href="{{ route('tasks.edit', $task->id) }}"
                                                            class="btn btn-light btn-sm text-primary mx-1" title="Edit">
                                                            <i class="ri-edit-line"></i>
                                                        </a>
                                                        @if (Route::has('tasks.delete'))
                                                            <form
                                                                action="{{ route('tasks.delete', $task->id) }}"
                                                                method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="btn btn-light btn-sm text-danger mx-1"
                                                                    title="Delete">
                                                                    <i class="ri-delete-bin-line"></i>
                                                                </button>
                                                            </form>
                                                        @else
                                                            <a href="#" class="btn btn-light btn-sm text-danger mx-1"
                                                                title="Delete">
                                                                <i class="ri-delete-bin-line"></i>
                                                            </a>
                                                        @endif
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
