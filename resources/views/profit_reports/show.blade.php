@extends('layouts.app')

@section('title')
    <title>Profit Report Details</title>
@endsection

@section('content')
    <div class="pagetitle">
        <h1>Profit Report Details</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('profits.index') }}">Profit Reports</a></li>
                <li class="breadcrumb-item active">Details</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-8">
                <!-- Project Details -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Project Information</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th style="width: 200px;">Project Title</th>
                                        <td>{{ $profit->project->title }}</td>
                                    </tr>
                                    <tr>
                                        <th>Client</th>
                                        <td>{{ $profit->project->client->user->first_name }} 
                                            {{ $profit->project->client->user->last_name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Expert</th>
                                        <td>
                                            @if($profit->project->projectAssigns->first())
                                                {{ $profit->project->projectAssigns->first()->expert->user->first_name }}
                                                {{ $profit->project->projectAssigns->first()->expert->user->last_name }}
                                            @else
                                                No expert assigned
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Project Approval</th>
                                        <td>
                                            <span class="badge bg-{{ $profit->project->approval_status == 1 ? 'success' : 'warning' }}">
                                                {{ ucfirst($profit->project->approval_status == 1 ? 'Approved' : 'Pending') }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Project Status</th>
                                        <td>
                                            <span class="badge bg-success">
                                                {{ ucfirst($profit->project->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <h5 class="card-title mt-4">Financial Information</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th style="width: 200px;">Project Budget</th>
                                        <td>${{ number_format($profit->project_budget, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Expert Cost</th>
                                        <td>${{ number_format($profit->expert_cost, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Payment Amount</th>
                                        <td>${{ number_format($profit->payment_amount, 2) }}</td>
                                    </tr>
                                    <tr class="table-{{ $profit->net_profit >= 0 ? 'success' : 'danger' }}">
                                        <th>{{ $profit->net_profit <= 0 ? 'Net Loss' : 'Net Profit' }}</th>
                                        <td>${{ number_format($profit->net_profit, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Profit Percentage</th>
                                        <td>{{ number_format($profit->profit_percentage, 2) }}%</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Payment Details -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Payment Information</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th>Payment ID</th>
                                        <td>#{{ $profit->payment->id }}</td>
                                    </tr>
                                    <tr>
                                        <th>Amount</th>
                                        <td>${{ number_format($profit->payment->amount, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>
                                            <span class="badge bg-{{ $profit->payment->status === 'received' ? 'success' : 'warning' }}">
                                                {{ ucfirst($profit->payment->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    {{-- <tr>
                                        <th>type</th>
                                        <td>
                                            <span class="badge bg-{{ $profit->payment->type === 'credit' ? 'success' : 'warning' }}">
                                                {{ ucfirst($profit->payment->type) }}
                                            </span>
                                        </td>
                                    </tr> --}}
                                    <tr>
                                        <th>Date</th>
                                        <td>{{ $profit->payment->created_at->format('M d, Y') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Actions</h5>
                        <div class="d-grid gap-2">
                            <a href="{{ route('profits.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Back to List
                            </a>
                            
                            @if($profit->project->status === 'completed' && auth()->user()->can('manage reports'))
                                <form action="{{ route('profits.calculate', ['project' => $profit->project_id, 'payment' => $profit->payment_id]) }}" 
                                      method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="bi bi-calculator"></i> Recalculate Profit
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
