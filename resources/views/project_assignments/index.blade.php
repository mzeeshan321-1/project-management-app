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
                                                <td class="text-center align-middle">
                                                    {{ $project_assignment->note ?? 'N/A' }}
                                                </td>
                                                <td class="text-center align-middle">{{ $project_assignment->budget }}</td>
                                                <td class="text-center align-middle">
                                                    <div class="dropdown position-static">
                                                        <button class="btn btn-light btn-sm rounded-circle" type="button"
                                                            data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="bi bi-three-dots-vertical"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end"
                                                            style="position: fixed;">
                                                            @can('assign projects')
                                                                <li>
                                                                    <a class="dropdown-item"
                                                                        href="{{ route('project_assignments.edit', $project_assignment->id) }}">
                                                                        <i class="bi bi-pencil me-2"></i> Edit
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <form
                                                                        action="{{ route('project_assignments.delete', $project_assignment->id) }}"
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
