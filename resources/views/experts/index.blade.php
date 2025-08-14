@extends('layouts.app')

@section('title')
    <title>Experts</title>
@endsection

@section('content')
    <div class="pagetitle">
        <h1>Experts</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Experts</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title">Experts Detailed List</h5>
                            @can('manage experts')
                                <div class="col text-end" title="Create Expert">
                                    <a href="{{ route('experts.create') }}" class="btn btn-primary">
                                        <i class="ri-add-fill"></i> Create Experts
                                    </a>
                                </div>
                            @endcan
                        </div>
                        <!-- Table with centered content -->
                        <div class="table-responsive">
                            <table class="table datatable text-nowrap">
                                <thead>
                                    <tr class="text-center">
                                        <th class="text-center align-middle">E.ID</th>
                                        <th class="align-middle">Full Name</th>
                                        <!-- Removed text-center for left alignment -->
                                        <th class="text-center align-middle">Contact</th>
                                        <th class="text-center align-middle">Address</th>
                                        <th class="text-center align-middle">Specialization</th>
                                        <th class="text-center align-middle">Skills</th>
                                        <th class="text-center align-middle">Last Login</th>
                                        <th class="text-center align-middle">Status</th>
                                        <th class="text-center align-middle">Action</th>
                                    </tr>
                                </thead>
                                @if ($experts->isNotEmpty())
                                    <tbody>
                                        @foreach ($experts as $expert)
                                            <tr>
                                                <td class="text-center align-middle">{{ $expert->id }}</td>
                                                <td class="align-middle">
                                                    <!-- Removed text-center for left alignment -->
                                                    <div class="d-flex">
                                                        @if ($expert->user->image)
                                                            <img src="{{ asset('images/' . $expert->user->image) }}"
                                                                alt="Profile" class="rounded-circle me-2" width="40"
                                                                height="40">
                                                        @else
                                                            <div class="rounded-circle d-flex justify-content-center align-items-center me-2"
                                                                style="width: 40px; height: 40px; font-size: 1.5em; background-color: #e6e5e5; color: #515050;">
                                                                <span class="fs-5 fw-bold">
                                                                    {{ strtoupper(substr($expert->user->first_name, 0, 1)) }}</span>
                                                            </div>
                                                        @endif
                                                        <div class="d-flex flex-column">
                                                            <p class="bold mb-0"> {{ $expert->user->first_name }}
                                                                {{ $expert->user->last_name }}
                                                            </p>
                                                            <span class="small">{{ $expert->user->email }}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center align-middle">
                                                    {{ $expert->user->contact ?? 'N/A' }}
                                                </td>
                                                <td class="text-center align-middle">
                                                    {{ $expert->user->address ?? 'N/A' }}
                                                </td>
                                                <td class="text-center align-middle">
                                                    {{ $expert->specialization ?? 'N/A' }}
                                                </td>
                                                <td class="text-center align-middle">{{ $expert->skills ?? 'N/A' }}
                                                </td>
                                                <td class="text-center align-middle">
                                                    {{ $expert->user->last_login ? \Carbon\Carbon::parse($expert->user->last_login)->format('Y-m-d') : 'N/A' }}
                                                </td>
                                                <td class="text-center align-middle">
                                                    @if ($expert->user->status == 'active')
                                                        <span
                                                            class="badge bg-success">{{ strtoupper($expert->user->status) }}</span>
                                                    @elseif ($expert->user->status == 'inactive')
                                                        <span
                                                            class="badge bg-danger">{{ strtoupper($expert->user->status) }}</span>
                                                    @elseif ($expert->user->status == 'suspended')
                                                        <span
                                                            class="badge bg-danger">{{ strtoupper($expert->user->status) }}</span>
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
                                                            @can('manage experts')
                                                                <li>
                                                                    <a class="dropdown-item"
                                                                        href="{{ route('experts.edit', $expert->id) }}">
                                                                        <i class="bi bi-pencil me-2"></i> Edit
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <form
                                                                        action="{{ route('experts.delete', $expert->id) }}"
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
