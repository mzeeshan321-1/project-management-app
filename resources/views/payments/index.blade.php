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
                            <table class="table datatable text-nowrap">
                                <thead>
                                    <tr class="text-center">
                                        <th class="text-center align-middle">P.ID</th>
                                        <th class="text-center align-middle">Project</th>
                                        <th class="align-middle">Send To</th>
                                        <th class="text-center align-middle">Amount</th>
                                        <th class="text-center align-middle">Note</th>
                                        {{-- <th class="text-center align-middle">Invoice</th> --}}
                                        <th class="text-center align-middle">Type</th>
                                        <th class="text-center align-middle">Status</th>
                                        <th class="text-center align-middle">Action</th>
                                    </tr>
                                </thead>
                                @if ($payments->isNotEmpty())
                                    <tbody>
                                        @foreach ($payments as $payment)
                                            <tr>
                                                <td class="text-center align-middle">{{ $payment->id }}</td>
                                                <td class="text-center align-middle">{{ $payment->project->title }}</td>
                                                <td class="text-center align-middle">
                                                    {{ $payment->receiver ? $payment->receiver->first_name . ' ' . $payment->receiver->last_name : 'N/A' }}</td>
                                                <td class="text-center align-middle">{{ $payment->amount ?? 'N/A' }}
                                                </td>
                                                {{-- <td class="text-center align-middle">{{ $payment->upload_invoice ?? 'N/A' }}</td> --}}
                                                <td class="text-center align-middle">{{ $payment->note ?? 'N/A' }}</td>
                                                <td class="text-center align-middle">
                                                    @if ($payment->type == 'debit')
                                                        <span
                                                            class="badge bg-info">{{ strtoupper($payment->type) }}</span>
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
                                                <td class="text-center align-middle">
                                                    <div class="d-flex justify-content-center">
                                                        <a href="{{ route('payments.edit', $payment->id) }}"
                                                            class="btn btn-light btn-sm text-primary mx-1" title="Edit">
                                                            <i class="ri-edit-line"></i>
                                                        </a>
                                                        @if (Route::has('payments.delete'))
                                                            <form action="{{ route('payments.delete', $payment->id) }}"
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
