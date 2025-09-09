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
                            <table class="table datatable">
                                <thead>
                                    <tr class="text-center">
                                        <th class="text-center align-middle">F.ID</th>
                                        <th class="text-center align-middle">File Name</th>
                                        <th class="text-center align-middle">Project</th>
                                        @role('middleman')
                                            <th class="align-middle">Uploaded By</th>
                                        @endrole
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
                                                <td class="text-center align-middle">
                                                    <a class="fw-bold" href="{{ asset('images/' . $file->file_name) }}" target="_blank">
                                                        {{ $file->file_name ? substr($file->file_name, 0, strrpos($file->file_name, '.')) : 'N/A' }}
                                                    </a>
                                                </td>
                                                <td class="text-center align-middle">
                                                    <a class="fw-bold"
                                                        href="{{ route('projects.show', $file->project->id) }}">
                                                        {{ $file->project->title }}
                                                    </a>
                                                </td>
                                                @role('middleman')
                                                    <td class="text-center align-middle">
                                                        {{ $file->user ? $file->user->first_name . ' ' . $file->user->last_name : 'N/A' }}
                                                    </td>
                                                @endrole
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
                                                    <div class="dropdown position-static">
                                                        <button class="btn btn-light btn-sm rounded-circle" type="button"
                                                            data-bs-toggle="dropdown" aria-expanded="false">
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
                                                                <a href="{{ route('projects.show', $file->project->id) }}"
                                                                    class="dropdown-item">
                                                                    <i class="ri-eye-line"></i> Show
                                                                </a>
                                                            </li>
                                                            @role(['middleman', 'expert'])
                                                                <li>
                                                                    <a class="dropdown-item"
                                                                        href="{{ route('files.edit', $file->id) }}">
                                                                        <i class="bi bi-pencil me-2"></i> Edit
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <form action="{{ route('files.delete', $file->id) }}"
                                                                        method="POST">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="dropdown-item text-danger"
                                                                            onclick="return confirm('Are you sure you want to delete this file?')">
                                                                            <i class="bi bi-trash me-2"></i> Delete
                                                                        </button>
                                                                    </form>
                                                                </li>
                                                            @endrole
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
