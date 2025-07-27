@extends('layouts.app')

@section('title')
    <title>Project Assignments</title>
@endsection

@section('content')
    <div class="pagetitle">
        <h1>Project Assignments</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Project Assignments</li>
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
                            <h5 class="card-title">Project Assignment Details</h5>
                                <div class="col text-end" title="Create Project Assignments">
                                    <a href="{{ route('project_assignments.create') }}" class="btn btn-primary">
                                        <i class="ri-add-fill"></i> Create Project Assignments
                                    </a>
                                </div>
                        </div>
                        <!-- Table with centered content -->
                        <div class="table-responsive">
                            <table class="table datatable text-nowrap">
                                <thead>
                                    <tr class="text-center">
                                        <th class="text-center align-middle">Pa .ID</th>
                                        <th class="text-center align-middle">Expert</th>
                                        <th class="text-center align-middle">Project</th>
                                        <th class="text-center align-middle">Note</th>
                                        <th class="text-center align-middle">Budget</th>
                                        <th class="text-center align-middle">Action</th>
                                    </tr>
                                </thead>
                                @if ($project_assignments->isNotEmpty())
                                    <tbody>
                                        @foreach ($project_assignments as $project_assignment)
                                            <tr>
                                                <td class="text-center align-middle">{{ $project_assignment->id }}</td>
                                                <td class="text-center align-middle">
                                                    {{ $project_assignment->expert->user->first_name }}
                                                    {{ $project_assignment->expert->user->last_name }}</td>

                                                <td class="align-middle">{{ $project_assignment->project->title }}</td>
                                                <td class="text-center align-middle">{{ $project_assignment->note ?? 'N/A' }}
                                                </td>
                                                <td class="text-center align-middle">{{ $project_assignment->budget }}</td>
                                                <td class="text-center align-middle">
                                                    <div class="d-flex justify-content-center">
                                                        <a href="{{ route('project_assignments.edit', $project_assignment->id) }}"
                                                            class="btn btn-light btn-sm text-primary mx-1" title="Edit">
                                                            <i class="ri-edit-line"></i>
                                                        </a>
                                                        @if (Route::has('project_assignments.delete'))
                                                            <form action="{{ route('project_assignments.delete', $project_assignment->id) }}"
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
