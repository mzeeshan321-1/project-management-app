@extends('layouts.app')

@section('title')
    <title>Projects</title>
@endsection

@section('content')
    <div class="pagetitle">
        <h1>Projects</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Projects</li>
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
                            <h5 class="card-title">Projects Detailed List</h5>
                            @can('manage projects')
                                <div class="col text-end" title="Create Projects">
                                    <a href="{{ route('projects.create') }}" class="btn btn-primary">
                                        <i class="ri-add-fill"></i> Create Projects
                                    </a>
                                </div>
                            @endcan
                        </div>
                        <!-- Table with centered content -->
                        <div class="table-responsive">
                            <table class="table datatable text-nowrap">
                                <thead>
                                    <tr class="text-center">
                                        <th class="text-center align-middle">P.ID</th>
                                        @role('middleman')
                                        <th class="text-center align-middle">Client</th>
                                        @elserole('client')
                                        <th class="text-center align-middle">Tanent</th>
                                        @endrole
                                        <th class="align-middle">Title</th>
                                        <th class="text-center align-middle">Description</th>
                                        <th class="text-center align-middle">Start Date</th>
                                        <th class="text-center align-middle">Deadline</th>
                                        <th class="text-center align-middle">Budget</th>
                                        <th class="text-center align-middle">Status</th>
                                        <th class="text-center align-middle">Action</th>
                                    </tr>
                                </thead>
                                @if ($projects->isNotEmpty())
                                    <tbody>
                                        @foreach ($projects as $project)
                                            <tr>
                                                <td class="text-center align-middle">{{ $project->id }}</td>
                                                @role('middleman')
                                                    <td class="text-center align-middle">
                                                        {{ $project->client->user->first_name }}
                                                        {{ $project->client->user->last_name }}</td>
                                                    @elserole('client')
                                                    <td class="text-center align-middle">
                                                        {{ $project->tanent->user->first_name }}
                                                        {{ $project->tanent->user->last_name }}</td>
                                                @endrole
                                                <td class="align-middle">{{ $project->title }}</td>
                                                <td class="text-center align-middle">{{ $project->description ?? 'N/A' }}
                                                </td>
                                                <td class="text-center align-middle">
                                                    {{ $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('d-M-Y') : 'N/A' }}
                                                </td>
                                                <td class="text-center align-middle">
                                                    {{ $project->deadline ? \Carbon\Carbon::parse($project->deadline)->format('d-M-Y') : 'N/A' }}
                                                </td>
                                                <td class="text-center align-middle">{{ $project->budget ?? 'N/A' }}</td>
                                                <td class="text-center align-middle">
                                                    @if ($project->status == 'in_progress')
                                                        <span
                                                            class="badge bg-info">{{ strtoupper($project->status) }}</span>
                                                    @elseif ($project->status == 'completed')
                                                        <span
                                                            class="badge bg-success">{{ strtoupper($project->status) }}</span>
                                                    @elseif ($project->status == 'inactive')
                                                        <span
                                                            class="badge bg-secondary">{{ strtoupper($project->status) }}</span>
                                                    @elseif ($project->status == 'cancelled')
                                                        <span
                                                            class="badge bg-danger">{{ strtoupper($project->status) }}</span>
                                                    @endif
                                                </td>
                                                <td class="text-center align-middle">
                                                    <div class="d-flex justify-content-center">
                                                        <a href="{{ route('projects.show', $project->id) }}"
                                                            class="btn btn-light btn-sm text-secondary mx-1"
                                                            title="View Details">
                                                            <i class="ri-eye-line"></i>
                                                        </a>
                                                        <a href="{{ route('projects.edit', $project->id) }}"
                                                            class="btn btn-light btn-sm text-primary mx-1" title="Edit">
                                                            <i class="ri-edit-line"></i>
                                                        </a>
                                                        @if (Route::has('projects.delete'))
                                                            <form action="{{ route('projects.delete', $project->id) }}"
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
