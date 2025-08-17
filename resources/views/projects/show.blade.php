@extends('layouts.app')

@section('title')
    <title>Project Details</title>
@endsection

@section('content')
    <div class="pagetitle">
        <h1>Project Details</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Projects</a></li>
                <li class="breadcrumb-item active">{{ $project->title }}</li>
            </ol>
        </nav>
    </div>

    <section class="section profile">
        <div class="row">
            <!-- Project Overview Card -->
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                        <h2 class="text-center mb-3">{{ $project->title }}</h2>
                        @role(['middleman', 'client'])
                            <h3>
                                <span class="text-muted small">Budget: </span>
                                <span class="fw-bold">${{ number_format($project->budget, 2) }}</span>
                            </h3>
                        @endrole
                        <div class="project-status mb-2">
                            @if ($project->status == 'in_progress')
                                <span class="badge bg-primary px-3 py-2">In Progress</span>
                            @elseif($project->status == 'completed')
                                <span class="badge bg-success px-3 py-2">Completed</span>
                            @elseif($project->status == 'cancelled')
                                <span class="badge bg-danger px-3 py-2">Cancelled</span>
                            @else
                                <span class="badge bg-secondary px-3 py-2">Inactive</span>
                            @endif
                        </div>
                        @can('update project status')
                            <span class="text-muted small fst-italic">Last Updated:
                                {{ $project->updated_at->diffForHumans() }}</span>
                        @endcan
                        <div class="project-dates mt-4 text-center">
                            <p class="small mb-1">
                                <i class="bi bi-calendar-event text-primary"></i> Start Date:
                                <span
                                    class="fw-bold">{{ $project->start_date ? date('M d, Y', strtotime($project->start_date)) : 'Not Set' }}</span>
                            </p>
                            <p class="small">
                                <i class="bi bi-calendar-check text-danger"></i> Deadline:
                                <span
                                    class="fw-bold">{{ $project->deadline ? date('M d, Y', strtotime($project->deadline)) : 'Not Set' }}</span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Project Actions Card -->
                @can('update project status')
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Project Actions</h5>
                            <form action="{{ route('projects.updateStatus', $project->id) }}" method="POST" class="mb-3">
                                @csrf
                                @method('PATCH')
                                <div class="form-group">
                                    <label for="status" class="form-label">Update Status</label>
                                    <select class="form-select" name="status" id="status">
                                        <option value="in_progress" {{ $project->status == 'in_progress' ? 'selected' : '' }}>In
                                            Progress</option>
                                        <option value="completed" {{ $project->status == 'completed' ? 'selected' : '' }}>
                                            Completed</option>
                                        <option value="inactive" {{ $project->status == 'inactive' ? 'selected' : '' }}>
                                            Inactive</option>
                                        <option value="cancelled" {{ $project->status == 'cancelled' ? 'selected' : '' }}>
                                            Cancelled</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary mt-3 w-100">Update Status</button>
                            </form>
                            @can('manage projects')
                                <a href="{{ route('projects.edit', $project->id) }}" class="btn btn-outline-primary w-100 mb-2">
                                    <i class="bi bi-pencil-square"></i> Edit Project
                                </a>
                            @endcan
                        </div>
                    </div>
                @endcan

                <!-- Approval Card -->
                @role('client')
                    @if ($project->status == 'completed')
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Project Approval</h5>
                                @if ($project->status == 'completed' && !$project->approval_status)
                                <p>Thank you for taking the time to review our work. Your approval is required to finalize the project and we appreciate your support.</p>
                                    <button type="button" class="btn btn-success w-100" data-bs-toggle="modal"
                                        data-bs-target="#approvalModal">
                                        <i class="bi bi-check-circle"></i> Approve Project
                                    </button>
                                @elseif($project->approval_status)
                                    <div class="alert alert-success text-center" role="alert">
                                        Project Approved!
                                    </div>
                                @else
                                    <div class="alert alert-info text-center" role="alert">
                                        Project must be completed to approve.
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                @endrole
            </div>

            <!-- Project Details and Documents -->
            <div class="col-xl-8">
                <!-- Project Details Tab -->
                <div class="card">
                    <div class="card-body pt-3">
                        <ul class="nav nav-tabs nav-tabs-bordered">
                            <li class="nav-item">
                                <button class="nav-link active" data-bs-toggle="tab"
                                    data-bs-target="#details">Details</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#documents">Documents</button>
                            </li>
                            @can('update project status')
                                <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tasks">Tasks</button>
                                </li>
                            @endcan
                        </ul>

                        <div class="tab-content pt-3">
                            <!-- Details Tab -->
                            <div class="tab-pane fade show active" id="details">
                                <h5 class="card-title">Project Description</h5>
                                <p class="small fst-italic">{{ $project->description ?? 'No description available.' }}</p>

                                <h5 class="card-title">Project Information</h5>
                                <div class="table-responsive">
                                    <table class="table table-borderless table-hover align-middle">
                                        <tbody>
                                            @can('manage projects')
                                                <tr>
                                                    <th class="bg-light" style="width: 30%">Client</th>
                                                    <td>{{ $project->client->user->first_name }}
                                                        {{ $project->client->user->last_name }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="bg-light">Assigned Expert</th>
                                                    <td>
                                                        @if ($project->projectAssigns && $project->projectAssigns->first())
                                                            @php $assignment = $project->projectAssigns->first(); @endphp
                                                            {{ $assignment->expert->user->first_name }}
                                                            {{ $assignment->expert->user->last_name }}
                                                        @else
                                                            <span class="text-muted">No expert assigned</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endcan
                                            @can('update project status')
                                                <tr>
                                                    <th class="bg-light">Task Overview</th>
                                                    <td>
                                                        <div class="d-flex gap-2 flex-wrap">
                                                            <span class="badge bg-primary">Total:
                                                                {{ $project->tasks->count() }}</span>
                                                            <span class="badge bg-success">Completed:
                                                                {{ $project->tasks->where('status', 'completed')->count() }}</span>
                                                            <span class="badge bg-warning">Pending:
                                                                {{ $project->tasks->where('status', 'pending')->count() }}</span>
                                                            <span class="badge bg-info">In Progress:
                                                                {{ $project->tasks->where('status', 'in_progress')->count() }}</span>
                                                            <span class="badge bg-secondary">On Hold:
                                                                {{ $project->tasks->where('status', 'on_hold')->count() }}</span>
                                                            <span class="badge bg-danger">Cancelled:
                                                                {{ $project->tasks->where('status', 'cancelled')->count() }}</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endcan
                                            <tr>
                                                <th class="bg-light">Documents</th>
                                                <td>
                                                    <span class="badge bg-primary">{{ $project->files->count() }}
                                                        file(s)</span>
                                                    @if ($project->files->count() > 0)
                                                        <small class="text-muted ms-2">Last uploaded:
                                                            {{ $project->files->sortByDesc('created_at')->first()->created_at->format('M d, Y') }}</small>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="bg-light">Duration</th>
                                                <td>
                                                    @if ($project->start_date && $project->deadline)
                                                        @php
                                                            $start = \Carbon\Carbon::parse($project->start_date);
                                                            $end = \Carbon\Carbon::parse($project->deadline);
                                                            $duration = $start->diffInDays($end);
                                                        @endphp
                                                        {{ $duration }} days
                                                        <small class="text-muted">
                                                            ({{ $start->isPast() ? $start->diffForHumans() : 'Starts ' . $start->diffForHumans() }})
                                                        </small>
                                                    @else
                                                        <span class="text-muted">Timeline not set</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="bg-light">Created</th>
                                                <td>
                                                    {{ $project->created_at->format('M d, Y') }}
                                                    <small
                                                        class="text-muted">({{ $project->created_at->diffForHumans() }})</small>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Documents Tab -->
                            <div class="tab-pane fade" id="documents">
                                <div class="documents-list">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="card-title">Project Documents</h5>
                                        @can('upload project deliverables')
                                            <a href="{{ route('files.create', ['project_id' => $project->id]) }}"
                                                class="btn btn-primary btn-sm">
                                                <i class="bi bi-file-earmark-arrow-up"></i> Upload Document
                                            </a>
                                        @endcan
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle text-center">
                                            <thead>
                                                <tr>
                                                    <th class="text-center align-middle">Uploaded By</th>
                                                    <th class="text-center align-middle">Type</th>
                                                    <th class="text-center align-middle">Description</th>
                                                    <th class="text-center align-middle">Date</th>
                                                    <th class="text-center align-middle">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($project->files as $file)
                                                    <tr>
                                                        <td class="align-middle">{{ $file->user->first_name }}
                                                            {{ $file->user->last_name }}
                                                        </td>
                                                        <td class="text-center align-middle">
                                                            @if ($file->file_type == 'image')
                                                                <span
                                                                    class="badge bg-primary">{{ strtoupper($file->file_type) }}</span>
                                                            @elseif ($file->file_type == 'document')
                                                                <span
                                                                    class="badge bg-danger">{{ strtoupper($file->file_type) }}</span>
                                                            @endif
                                                        </td>
                                                        <td class="align-middle">{{ Str::limit($file->description, 30) }}
                                                        </td>
                                                        <td class="align-middle">{{ $file->created_at->format('M d, Y') }}
                                                        </td>
                                                        <td>
                                                            <div class="dropdown position-static">
                                                                <button class="btn btn-light btn-sm rounded-circle"
                                                                    type="button" data-bs-toggle="dropdown"
                                                                    aria-expanded="false">
                                                                    <i class="bi bi-three-dots-vertical"></i>
                                                                </button>
                                                                <ul class="dropdown-menu dropdown-menu-end"
                                                                    style="position: fixed;">
                                                                    <li>
                                                                        <a class="dropdown-item" download
                                                                            href="{{ url('images/', $file->file_name) }}">
                                                                            <i class="bi bi-download me-2"></i> Download
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a class="dropdown-item"
                                                                            href="{{ route('files.edit', $file->id) }}">
                                                                            <i class="bi bi-pencil me-2"></i> Edit
                                                                        </a>
                                                                    </li>
                                                                    @can('manage project deliverables')
                                                                        <li>
                                                                            <form
                                                                                action="{{ route('files.delete', $file->id) }}"
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
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="text-center">No documents uploaded yet.
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            @can('update project status')
                                <!-- Tasks Tab -->
                                <div class="tab-pane fade" id="tasks">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="card-title">Project Tasks</h5>
                                        @can('manage tasks')
                                            <a href="{{ route('tasks.create', ['project_id' => $project->id]) }}"
                                                class="btn btn-primary btn-sm">
                                                <i class="bi bi-plus-lg"></i> Add Task
                                            </a>
                                        @endcan
                                    </div>

                                    <div class="task-list">
                                        @forelse($project->tasks as $task)
                                            <div class="card mb-3">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <h5 class="card-title mb-0">{{ $task->title }}</h5>
                                                        <div class="task-status">
                                                            @if ($task->status == 'pending')
                                                                <span class="badge bg-warning">Pending</span>
                                                            @elseif($task->status == 'in_progress')
                                                                <span class="badge bg-info">In Progress</span>
                                                            @elseif($task->status == 'completed')
                                                                <span class="badge bg-success">Completed</span>
                                                            @elseif($task->status == 'on_hold')
                                                                <span class="badge bg-secondary">On Hold</span>
                                                            @else
                                                                <span class="badge bg-danger">Cancelled</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <p class="card-text mt-2">{{ Str::limit($task->description, 100) }}</p>
                                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                                        <div class="task-meta">
                                                            <small class="text-muted">
                                                                <i class="bi bi-calendar"></i> Due:
                                                                {{ $task->due_date ? date('M d, Y', strtotime($task->due_date)) : 'Not Set' }}
                                                            </small>
                                                        </div>
                                                        @can('manage tasks')
                                                            <div class="task-actions">
                                                                <a href="{{ route('tasks.edit', $task->id) }}"
                                                                    class="btn btn-sm btn-outline-primary">
                                                                    <i class="bi bi-pencil"></i>
                                                                </a>
                                                            </div>
                                                        @endcan
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="text-center py-3">
                                                <p class="text-muted">No tasks created for this project yet.</p>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
            <!-- Approval Modal -->
            <div class="modal fade" id="approvalModal" tabindex="-1" aria-labelledby="approvalModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header d-flex justify-content-center">
                            <h5 class="modal-title" id="approvalModalLabel">Confirm Project Approval</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body px-5">
                            <p>If you are satisfied with this project, please proceed to make the payment to finalize the
                                approval.</p>
                        </div>
                        <div class="modal-footer d-flex justify-content-center">
                            <a href="{{ route('payments.create') }}" class="btn btn-primary ms-2">Proceed to Payment</a>
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
