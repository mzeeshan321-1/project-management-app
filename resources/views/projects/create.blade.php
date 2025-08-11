@extends('layouts.app')

@section('title')
    <title>Create Project</title>
@endsection

@section('content')
    <div class="pagetitle">
        <h1>Create Project</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Projects</a></li>
                <li class="breadcrumb-item active">Create Project</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <div class="text-end mb-2" title="Back to Project">
        <a href="{{ route('projects.index') }}" class="btn btn-primary"><i class="ri-arrow-left-s-line"></i></a>
    </div>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Project Details</h5>
            <!-- Floating Labels Form -->
            <form method="post" action="{{ route('projects.store') }}" class="row g-3">
                @csrf
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="title" value="{{ old('title') }}" id="Title"
                            placeholder="Title">
                        <label for="Title">Title</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <select class="form-select" name="client_id" id="Client" aria-label="Client" required>
                            <option class="text-center" value="" selected disabled>--- Select a Client ---</option>
                            @if ($clients->isNotEmpty())
                                @foreach ($clients as $client)
                                    <option value="{{ $client->id }}">
                                        {{ $client->user->first_name }} {{ $client->user->last_name }}</option>
                                @endforeach
                            @endif
                        </select>
                        <label for="Client">Client</label>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-floating">
                        <textarea class="form-control" name="description" id="Description" placeholder="Description" style="height: 100px;">{{ old('Description') }}</textarea>
                        <label for="Description">Description</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="start_date" id="start_datepicker"
                            value="{{ old('start_date') }}" placeholder="Start Date" required autocomplete="off">
                        <label for="start_datepicker">Start Date</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="text" name="deadline" class="form-control" placeholder="Deadline"
                            id="deadline_datepicker" value="{{ old('deadline') }}" required>
                        <label for="deadline_datepicker">Deadline</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="text" class="form-control"value="{{ old('budget') }}" name="budget" id="Budget"
                            placeholder="Budget" required>
                        <label for="Budget">Budget</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <select class="form-select" name="status" id="Status" aria-label="Status" required>
                            <option selected value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive
                            </option>
                            <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>In Progress
                            </option>
                            <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed
                            </option>
                            <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled
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
            $("#start_datepicker, #deadline_datepicker").datepicker({
                dateFormat: "dd-M-yy",
                changeMonth: true,
                changeYear: true,
            });
        });
    </script>
@endsection
