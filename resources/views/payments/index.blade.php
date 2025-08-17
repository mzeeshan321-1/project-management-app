@extends('layouts.app')

@section('title')
    <title>Payments</title>
@endsection

@section('content')
    <div class="pagetitle">
        <h1>Payments</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Payments</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    {{-- {{ dd($payments) }} --}}
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title">Payments Detailed List</h5>
                            @can('manage payments')
                                <div class="col text-end" title="Create Payments">
                                    <a href="{{ route('payments.create') }}" class="btn btn-primary">
                                        <i class="ri-add-fill"></i> Create Payments
                                    </a>
                                </div>
                            @endcan
                        </div>
                        <!-- Table with centered content -->
                        <div class="table-responsive">
                            <table class="table datatable">
                                <thead>
                                    <tr class="text-center">
                                        <th class="text-center align-middle">P.ID</th>
                                        <th class="text-center align-middle">Project</th>
                                        <th class="align-middle">Sender Name</th>
                                        <th class="align-middle">Receiver Name</th>
                                        <th class="text-center align-middle">Amount</th>
                                        <th class="text-center align-middle">Note</th>
                                        {{-- <th class="text-center align-middle">Invoice</th> --}}
                                        <th class="text-center align-middle">Type</th>
                                        <th class="text-center align-middle">Status</th>
                                        @can('manage payments')
                                            <th class="text-center align-middle">Action</th>
                                        @endcan
                                    </tr>
                                </thead>
                                @if ($payments->isNotEmpty())
                                    <tbody>
                                        @foreach ($payments as $payment)
                                            <tr>
                                                <td class="text-center align-middle">{{ $payment->id }}</td>
                                                <td class="text-center align-middle">{{ $payment->project->title }}</td>
                                                <td class="text-center align-middle">
                                                    {{ $payment->sender ? $payment->sender->first_name . ' ' . $payment->sender->last_name : 'N/A' }}
                                                </td>
                                                <td class="text-center align-middle">
                                                    {{ $payment->receiver ? $payment->receiver->first_name . ' ' . $payment->receiver->last_name : 'N/A' }}
                                                </td>
                                                <td class="text-center align-middle">{{ $payment->amount ?? 'N/A' }}
                                                </td>
                                                {{-- <td class="text-center align-middle">{{ $payment->upload_invoice ?? 'N/A' }}</td> --}}
                                                <td class="text-center align-middle">{{ $payment->note ?? 'N/A' }}</td>
                                                <td class="text-center align-middle">
                                                    @if ($payment->type == 'debit')
                                                        <span class="badge bg-info">{{ strtoupper($payment->type) }}</span>
                                                    @elseif ($payment->type == 'credit')
                                                        <span
                                                            class="badge bg-success">{{ strtoupper($payment->type) }}</span>
                                                    @elseif ($payment->type == 'return')
                                                        <span
                                                            class="badge bg-secondary">{{ strtoupper($payment->type) }}</span>
                                                    @endif
                                                </td>
                                                <td class="text-center align-middle">
                                                    @if ($payment->status == 'pending')
                                                        <span
                                                            class="badge bg-secondary">{{ strtoupper($payment->status) }}</span>
                                                    @elseif ($payment->status == 'received')
                                                        <span
                                                            class="badge bg-success">{{ strtoupper($payment->status) }}</span>
                                                    @elseif ($payment->status == 'returned')
                                                        <span
                                                            class="badge bg-danger">{{ strtoupper($payment->status) }}</span>
                                                    @endif
                                                </td>
                                                @can('manage payments')
                                                    <td class="text-center align-middle">
                                                        <div class="dropdown position-static">
                                                            <button class="btn btn-light btn-sm rounded-circle" type="button"
                                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i class="bi bi-three-dots-vertical"></i>
                                                            </button>
                                                            <ul class="dropdown-menu dropdown-menu-end"
                                                                style="position: fixed;">
                                                                <li>
                                                                    <a class="dropdown-item"
                                                                        href="{{ route('payments.edit', $payment->id) }}">
                                                                        <i class="bi bi-pencil me-2"></i> Edit
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <form action="{{ route('payments.delete', $payment->id) }}"
                                                                        method="POST">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="dropdown-item text-danger"
                                                                            onclick="return confirm('Are you sure you want to delete this file?')">
                                                                            <i class="bi bi-trash me-2"></i> Delete
                                                                        </button>
                                                                    </form>
                                                                </li>
                                                            </ul>
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
