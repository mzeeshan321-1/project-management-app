@extends('layouts.app')

@section('title')
    <title>Task Details</title>
@endsection

@section('content')
    <div class="pagetitle">
        <h1>Task Details</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('tasks.index') }}">Tasks</a></li>
                <li class="breadcrumb-item active">{{ $task->title }}</li>
            </ol>
        </nav>
    </div>

    <section class="section profile">
        <div class="row">
            <!-- Task Overview Card -->
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                        <h2 class="text-center mb-3">{{ $task->title }}</h2>
                        <div class="project-status mb-2">
                            @if ($task->status == 'in_progress')
                                <span class="badge bg-primary px-3 py-2">In Progress</span>
                            @elseif($task->status == 'on_hold')
                                <span class="badge bg-secondary px-3 py-2">On Hold</span>
                            @elseif($task->status == 'completed')
                                <span class="badge bg-success px-3 py-2">Completed</span>
                            @elseif($task->status == 'cancelled')
                                <span class="badge bg-danger px-3 py-2">Cancelled</span>
                            @else
                                <span class="badge bg-warning px-3 py-2">Pending</span>
                            @endif
                        </div>
                        <div class="project-dates mt-3 text-center">
                            <p class="small mb-1">
                                <i class="bi bi-calendar-event text-primary"></i> Due Date:
                                <span
                                    class="fw-bold">{{ $task->due_date ? date('M d, Y', strtotime($task->due_date)) : 'N/A' }}</span>
                            </p>
                        </div>
                        <span class="text-muted small fst-italic">Last Updated:
                            {{ $task->updated_at->diffForHumans() }}</span>
                    </div>
                </div>

                <!-- Task Actions Card -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Task Actions</h5>
                        <form action="{{ route('tasks.updateStatus', $task->id) }}" method="POST" class="mb-3">
                            @csrf
                            @method('PATCH')
                            <div class="form-group mb-3">
                                <label for="status" class="form-label">Update Status</label>
                                <select class="form-select" name="status" id="status">
                                    <option value="in_progress" {{ $task->status == 'in_progress' ? 'selected' : '' }}>In
                                        Progress</option>
                                    <option value="completed" {{ $task->status == 'completed' ? 'selected' : '' }}>
                                        Completed</option>
                                    <option value="inactive" {{ $task->status == 'pending' ? 'selected' : '' }}>
                                        Pending</option>
                                    <option value="cancelled" {{ $task->status == 'cancelled' ? 'selected' : '' }}>
                                        Cancelled</option>
                                    <option value="cancelled" {{ $task->status == 'on_hold' ? 'selected' : '' }}>
                                        On Hold</option>
                                </select>
                            </div>
                            @can('manage tasks')
                                <div class="form-group">
                                    <label for="priority" class="form-label">Update Priority</label>
                                    <select class="form-select" name="priority" id="priority">
                                        <option value="medium" {{ $task->priority == 'medium' ? 'selected' : '' }}>
                                            Medium</option>
                                        <option value="low" {{ $task->priority == 'low' ? 'selected' : '' }}>
                                            Low</option>
                                        <option value="high" {{ $task->priority == 'high' ? 'selected' : '' }}>
                                            High</option>
                                    </select>
                                </div>
                            @endcan
                            <button type="submit" class="btn btn-primary mt-3 w-100">Update</button>
                        </form>

                        <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-outline-primary w-100 mb-2">
                            <i class="bi bi-pencil-square"></i> Edit Task
                        </a>
                    </div>
                </div>
            </div>

            <!-- Task Details and Documents -->
            <div class="col-xl-8">
                <!-- Task Details Tab -->
                <div class="card">
                    <div class="card-body pt-3">
                        <ul class="nav nav-tabs nav-tabs-bordered">
                            <li class="nav-item">
                                <button class="nav-link active" data-bs-toggle="tab"
                                    data-bs-target="#details">Details</button>
                            </li>
                        </ul>

                        <div class="tab-content pt-3">
                            <!-- Details Tab -->
                            <div class="tab-pane fade show active" id="details">
                                <h5 class="card-title">Task Description</h5>
                                <p class="small fst-italic">{{ $task->description ?? 'No description available.' }}</p>

                                <h5 class="card-title">Task Information</h5>
                                <div class="table-responsive">
                                    <table class="table table-borderless table-hover align-middle">
                                        <tbody>
                                            <tr>
                                                <th class="bg-light">Task Overview</th>
                                                <td>
                                                    <div class="d-flex gap-2 flex-wrap">
                                                        <span class="badge bg-primary">Total:
                                                            {{ $task->count() }}</span>
                                                        <span class="badge bg-success">Completed:
                                                            {{ $task->where('status', 'completed')->count() }}</span>
                                                        <span class="badge bg-warning">Pending:
                                                            {{ $task->where('status', 'pending')->count() }}</span>
                                                        <span class="badge bg-info">In Progress:
                                                            {{ $task->where('status', 'in_progress')->count() }}</span>
                                                        <span class="badge bg-secondary">On Hold:
                                                            {{ $task->where('status', 'on_hold')->count() }}</span>
                                                        <span class="badge bg-danger">Cancelled:
                                                            {{ $task->where('status', 'cancelled')->count() }}</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="bg-light">Due Date</th>
                                                <td>
                                                    {{ $task->due_date ? date('M d, Y', strtotime($task->due_date)) : 'N/A' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="bg-light">Created</th>
                                                <td>
                                                    {{ $task->created_at->format('M d, Y') }}
                                                    <small
                                                        class="text-muted">({{ $task->created_at->diffForHumans() }})</small>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="bg-light">Task Priority</th>
                                                <td>
                                                    <div class="d-flex gap-2 flex-wrap">
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
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        // Auto-submit status form when selection changes
        document.getElementById('status')?.addEventListener('change', function() {
            this.closest('form').submit();
        });

        // File upload preview
        document.getElementById('file')?.addEventListener('change', function() {
            const fileName = this.files[0]?.name;
            if (fileName) {
                this.nextElementSibling.textContent = fileName;
            }
        });
    </script>
@endsection
