@extends('layouts.app')

@section('title')
    <title>Tanents</title>
@endsection

@section('content')
    <div class="pagetitle">
        <h1>Tanents</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Tanents</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title">Tanents Detailed List</h5>
                            @can('manage middleman')
                                <div class="col text-end pb-2" title="Create Tanent">
                                    <a href="{{ route('middleman.create') }}" class="btn btn-primary">
                                        <i class="ri-add-fill"></i> Create Tanent
                                    </a>
                                </div>
                            @endcan
                        </div>
                        <!-- Table with centered content -->
                        <div class="table-responsive">
                            <table class="table datatable text-nowrap">
                                <thead>
                                    <tr class="text-center">
                                        <th class="text-center align-middle">T.ID</th>
                                        <th class="align-middle">Full Name</th>
                                        <!-- Removed text-center for left alignment -->
                                        <th class="text-center align-middle">Contact</th>
                                        <th class="text-center align-middle">Address</th>
                                        <th class="text-center align-middle">Last Login</th>
                                        <th class="text-center align-middle">Status</th>
                                        @can('manage middleman')
                                            <th class="text-center align-middle">Action</th>
                                        @endcan
                                    </tr>
                                </thead>
                                @if ($tanents->isNotEmpty())
                                    <tbody>
                                        @foreach ($tanents as $tanent)
                                            <tr>
                                                <td class="text-center align-middle">{{ $tanent->id }}</td>
                                                <td class="align-middle"> <!-- Removed text-center for left alignment -->
                                                    <div class="d-flex">
                                                        @if ($tanent->user->image)
                                                            <img src="{{ asset('images/' . $tanent->user->image) }}"
                                                                alt="Profile" class="rounded-circle me-2" width="40"
                                                                height="40">
                                                        @else
                                                            <div class="rounded-circle d-flex justify-content-center align-items-center me-2"
                                                                style="width: 40px; height: 40px; font-size: 1.5em; background-color: #e6e5e5; color: #515050;">
                                                                <span class="fs-5 fw-bold">
                                                                    {{ strtoupper(substr($tanent->user->first_name, 0, 1)) }}</span>
                                                            </div>
                                                        @endif
                                                        <div class="d-flex flex-column">
                                                            <p class="bold mb-0"> {{ $tanent->user->first_name }}
                                                                {{ $tanent->user->last_name }}</p>
                                                            <span class="small">{{ $tanent->user->email }}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center align-middle">{{ $tanent->user->contact ?? 'N/A' }}
                                                </td>
                                                <td class="text-center align-middle">{{ $tanent->user->address ?? 'N/A' }}
                                                </td>
                                                <td class="text-center align-middle">
                                                    {{ $tanent->user->last_login ? \Carbon\Carbon::parse($tanent->user->last_login)->format('Y-m-d') : 'N/A' }}
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
                                                @can('manage middleman')
                                                    <td class="text-center align-middle">
                                                        <div class="d-flex justify-content-center">
                                                            <a href="{{ route('middleman.edit', $tanent->user->id) }}"
                                                                class="btn btn-light btn-sm text-primary mx-1" title="Edit">
                                                                <i class="ri-edit-line"></i>
                                                            </a>
                                                            @if (Route::has('middleman.delete'))
                                                                <form
                                                                    action="{{ route('middleman.delete', $tanent->user->id) }}"
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
                                                @endcan
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
