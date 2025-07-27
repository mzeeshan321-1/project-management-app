@extends('layouts.app')

@section('title')
    <title>Edit Task</title>
@endsection

@section('content')
    <div class="pagetitle">
        <h1>Edit Task</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('tasks.index') }}">Tasks</a></li>
                <li class="breadcrumb-item active">Edit Task</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <div class="text-end mb-2" title="Back to Tasks">
        <a href="{{ route('tasks.index') }}" class="btn btn-primary"><i class="ri-arrow-left-s-line"></i></a>
    </div>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Edit Task Details</h5>
            <!-- Floating Labels Form -->
            <form method="post" action="{{ route('tasks.update', $task->id) }}" class="row g-3">
                @csrf
                @method('PUT')
                <div class="col-md-6">
                    <div class="form-floating">
                        <select class="form-select" name="project_id" id="Project" aria-label="Project" required>
                            <option class="text-center" value="" selected disabled>--- Select a Project ---</option>
                            @if ($projects->isNotEmpty())
                                @foreach ($projects as $project)
                                    <option {{ $task->project_id == $project->id ? 'selected' : '' }} value="{{ $project->id }}">{{ $project->title }}</option>
                                @endforeach
                            @endif
                        </select>
                        <label for="Project">Project</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="title" value="{{ $task->title }}" id="Title"
                            placeholder="Title">
                        <label for="Title">Title</label>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-floating">
                        <textarea class="form-control" name="description" id="Description" placeholder="Description" style="height: 100px;">{{ $task->description }}</textarea>
                        <label for="Description">Description</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="due_date" id="due_datepicker"
                            value="{{ $task->due_date }}" placeholder="Due Date" autocomplete="off">
                        <label for="due_datepicker">Due Date</label>
                    </div>
                </div>
               <div class="col-md-6">
                    <div class="form-floating">
                        <input type="text" name="completed_at" class="form-control" placeholder="Completed At"
                            id="completedAt_datepicker" value="{{ $task->completed_at }}" required>
                        <label for="completedAt_datepicker">Completed At</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <select class="form-select" name="priority" id="Priority" aria-label="Priority" required>
                            <option selected value="low" {{ $task->priority == 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ $task->priority == 'medium' ? 'selected' : '' }}>Medium</option>                   
                            <option value="high" {{ $task->priority == 'high' ? 'selected' : '' }}>High</option>                   
                        </select>
                        <label for="Priority">Priority</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <select class="form-select" name="status" id="Status" aria-label="Status" required>
                            <option selected value="pending" {{ $task->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="on_hold" {{ $task->status == 'on_hold' ? 'selected' : '' }}>On Hold</option>
                            <option value="in_progress" {{ $task->status == 'in_progress' ? 'selected' : '' }}>In Progress
                            </option>
                            <option value="completed" {{ $task->status == 'completed' ? 'selected' : '' }}>Completed
                            </option>
                            <option value="cancelled" {{ $task->status == 'cancelled' ? 'selected' : '' }}>Cancelled
                            </option>
                        </select>
                        <label for="Status">Status</label>
                    </div>
                </div>
                <div class="text-center mt-5">
                    <input type="Reset" value="Reset" class="btn btn-light">
                    <input type="submit" value="Submit" class="btn btn-primary">
                </div>
            </form><!-- End floating Labels Form -->
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $("#due_datepicker").datepicker({
                dateFormat: "yy-mm-dd",
                changeMonth: true,
                changeYear: true,
            });

            $("#completedAt_datepicker").datetimepicker({
                dateFormat: "yy-mm-dd",
                timeFormat: "HH:mm",
                changeMonth: true,
                changeYear: true,
            });
        });
    </script>
@endsection
