@extends('layouts.app')

@section('title')
    <title>Files</title>
@endsection

@section('content')
    <div class="pagetitle">
        <h1>Files</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Files</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title">Files Detailed List</h5>
                            @can('upload project deliverables')
                                <div class="col text-end" title="Create Files">
                                    <a href="{{ route('files.create') }}" class="btn btn-primary">
                                        <i class="bi bi-file-earmark-arrow-up"></i> Upload Files
                                    </a>
                                </div>
                            @endcan
                        </div>
                        <!-- Table with centered content -->
                        <div class="table-responsive">
                            <table class="table datatable text-nowrap">
                                <thead>
                                    <tr class="text-center">
                                        <th class="text-center align-middle">F.ID</th>
                                        <th class="text-center align-middle">Project</th>
                                        @role('middleman')
                                        <th class="align-middle">Uploaded By</th>
                                        @endrole
                                        <th class="text-center align-middle">File Name</th>
                                        <th class="text-center align-middle">Description</th>
                                        <th class="text-center align-middle">File Type</th>
                                        <th class="text-center align-middle">Action</th>
                                    </tr>
                                </thead>
                                @if ($files->isNotEmpty())
                                    <tbody>
                                        @foreach ($files as $file)
                                            <tr>
                                                <td class="text-center align-middle">{{ $file->id }}</td>
                                                <td class="text-center align-middle">{{ $file->project->title }}</td>
                                                @role('middleman')
                                                    <td class="text-center align-middle">
                                                        {{ $file->user ? $file->user->first_name . ' ' . $file->user->last_name : 'N/A' }}
                                                    </td>
                                                @endrole
                                                <td class="text-center align-middle">{{ $file->file_name ?? 'N/A' }}</td>
                                                <td class="text-center align-middle">{{ $file->description ?? 'N/A' }}</td>
                                                <td class="text-center align-middle">
                                                    @if ($file->file_type == 'image')
                                                        <span
                                                            class="badge bg-primary">{{ strtoupper($file->file_type) }}</span>
                                                    @elseif ($file->file_type == 'document')
                                                        <span
                                                            class="badge bg-danger">{{ strtoupper($file->file_type) }}</span>
                                                    @endif
                                                </td>
                                                <td class="text-center align-middle">
                                                    <div class="d-flex justify-content-center">
                                                        <a href="{{ route('projects.show', $file->project->id) }}"
                                                            class="btn btn-light btn-sm text-secondary mx-1" title="View Details">
                                                            <i class="ri-eye-line"></i>
                                                        </a>
                                                        <a href="{{ route('files.edit', $file->id) }}"
                                                            class="btn btn-light btn-sm text-primary mx-1" title="Edit">
                                                            <i class="ri-edit-line"></i>
                                                        </a>
                                                        @if (Route::has('files.delete'))
                                                            <form action="{{ route('files.delete', $file->id) }}"
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
