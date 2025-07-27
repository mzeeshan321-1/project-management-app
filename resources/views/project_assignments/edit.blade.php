@extends('layouts.app')

@section('title')
    <title>Edit Project Assignment</title>
@endsection

@section('content')
    <div class="pagetitle">
        <h1>Edit Project Assignment</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('project_assignments.index') }}">Project Assignments</a></li>
                <li class="breadcrumb-item active">Edit Project Assignment</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <div class="text-end mb-2" title="Back to Project Assignments">
        <a href="{{ route('project_assignments.index') }}" class="btn btn-primary"><i class="ri-arrow-left-s-line"></i></a>
    </div>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Edit Project Assignment Details</h5>
            <!-- Floating Labels Form -->
            <form method="post" action="{{ route('project_assignments.update', $project_assignment->id) }}" enctype="multipart/form-data" class="row g-3" id="project_assignment_form') }}" class="row g-3">
                @csrf
                @method('PUT')
                <div class="col-md-4">
                    <div class="form-floating">
                        <select class="form-select" name="project_id" id="Project" aria-label="Project" required>
                            <option class="text-center" value="" selected disabled>--- Select a Project ---</option>
                            @if ($projects->isNotEmpty())
                                @foreach ($projects as $project)
                                    <option {{ $project->id == $project_assignment->project_id ? 'selected' : '' }} value="{{ $project->id }}">{{ $project->title }}</option>
                                @endforeach
                            @endif
                        </select>
                        <label for="Project">Project</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating">
                        <select class="form-select" name="expert_id" id="Expert" aria-label="Expert" required>
                            <option class="text-center" value="" selected disabled>--- Select an Expert ---</option>
                            @if ($experts->isNotEmpty())
                                @foreach ($experts as $expert)
                                    <option {{ $expert->id == $project_assignment->expert_id ? 'selected' : '' }} value="{{ $expert->id }}">{{ $expert->user->first_name }} {{ $expert->user->last_name }}</option>
                                @endforeach
                            @endif
                        </select>
                        <label for="Expert">Expert</label>
                    </div>
                </div>
                 <div class="col-md-4">
                    <div class="form-floating">
                        <input type="text" class="form-control"value="{{ $project_assignment->budget }}" name="budget" id="Budget"
                            placeholder="Budget" required>
                        <label for="Budget">Budget</label>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-floating">
                        <textarea class="form-control" name="note" id="Note" placeholder="Note" style="height: 100px;">{{ $project_assignment->note }}</textarea>
                        <label for="Note">Note</label>
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
