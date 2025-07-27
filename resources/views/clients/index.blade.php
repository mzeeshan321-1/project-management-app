@extends('layouts.app')

@section('title')
    <title>Clients</title>
@endsection

@section('content')
    <div class="pagetitle">
        <h1>Clients</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Clients</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title">Clients Detailed List</h5>
                            @can('manage clients')
                                <div class="col text-end" title="Create Clients">
                                    <a href="{{ route('clients.create') }}" class="btn btn-primary">
                                        <i class="ri-add-fill"></i> Create Clients
                                    </a>
                                </div>
                            @endcan
                        </div>
                        <!-- Table with centered content -->
                        <div class="table-responsive">
                            <table class="table datatable text-nowrap">
                                <thead>
                                    <tr class="text-center">
                                        <th class="text-center align-middle">C.ID</th>
                                        <th class="align-middle">Full Name</th>
                                        <!-- Removed text-center for left alignment -->
                                        <th class="text-center align-middle">Contact</th>
                                        <th class="text-center align-middle">Address</th>
                                        <th class="text-center align-middle">Industry</th>
                                        <th class="text-center align-middle">Last Login</th>
                                        <th class="text-center align-middle">Status</th>
                                        <th class="text-center align-middle">Action</th>
                                    </tr>
                                </thead>
                                @if ($clients->isNotEmpty())
                                    <tbody>
                                        @foreach ($clients as $client)
                                            <tr>
                                                <td class="text-center align-middle">{{ $client->id }}</td>
                                                <td class="align-middle">
                                                    <!-- Removed text-center for left alignment -->
                                                    <div class="d-flex">
                                                        @if ($client->user->image)
                                                            <img src="{{ asset('images/' . $client->user->image) }}"
                                                                alt="Profile" class="rounded-circle me-2" width="40"
                                                                height="40">
                                                        @else
                                                            <div class="rounded-circle d-flex justify-content-center align-items-center me-2"
                                                                style="width: 40px; height: 40px; font-size: 1.5em; background-color: #e6e5e5; color: #515050;">
                                                                <span class="fs-5 fw-bold">
                                                                    {{ strtoupper(substr($client->user->first_name, 0, 1)) }}</span>
                                                            </div>
                                                        @endif
                                                        <div class="d-flex flex-column">
                                                            <p class="bold mb-0"> {{ $client->user->first_name }}
                                                                {{ $client->user->last_name }}
                                                            </p>
                                                            <span class="small">{{ $client->user->email }}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center align-middle">
                                                    {{ $client->user->contact ?? 'N/A' }}
                                                </td>
                                                <td class="text-center align-middle">
                                                    {{ $client->user->address ?? 'N/A' }}
                                                </td>
                                                <td class="text-center align-middle">{{ $client->industry ?? 'N/A' }}
                                                </td>
                                                <td class="text-center align-middle">
                                                    {{ $client->user->last_login ? \Carbon\Carbon::parse($client->user->last_login)->format('Y-m-d') : 'N/A' }}
                                                </td>
                                                <td class="text-center align-middle">
                                                   @if ($client->user->status == 'active')
                                                        <span
                                                            class="badge bg-success">{{ strtoupper($client->user->status) }}</span>
                                                    @elseif ($client->user->status == 'inactive')
                                                        <span
                                                            class="badge bg-danger">{{ strtoupper($client->user->status) }}</span>
                                                    @elseif ($client->user->status == 'suspended')
                                                        <span
                                                            class="badge bg-danger">{{ strtoupper($client->user->status) }}</span>
                                                    @endif
                                                </td>
                                                <td class="text-center align-middle">
                                                    <div class="d-flex justify-content-center">
                                                        <a href="{{ route('clients.edit', $client->id) }}"
                                                            class="btn btn-light btn-sm text-primary mx-1" title="Edit">
                                                            <i class="ri-edit-line"></i>
                                                        </a>
                                                        @if (Route::has('clients.delete'))
                                                            <form action="{{ route('clients.delete', $client->id) }}"
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
