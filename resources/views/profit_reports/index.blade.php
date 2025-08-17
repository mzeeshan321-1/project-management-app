@extends('layouts.app')

@section('title')
    <title>Profit Reports</title>
@endsection

@section('content')
    <div class="pagetitle">
        <h1>Profit Reports</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Profit Reports</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <!-- Summary Stats -->
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xxl-3 col-md-6">
                                <div class="card info-card revenue-card">
                                    <div class="card-body">
                                        <h5 class="card-title">Total Projects</h5>
                                        <div class="d-flex align-items-center">
                                            <div
                                                class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                                <i class="bi bi-folder"></i>
                                            </div>
                                            <div class="ps-3">
                                                <h6>{{ $profits->count() }}</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xxl-3 col-md-6">
                                <div class="card info-card sales-card">
                                    <div class="card-body">
                                        <h5 class="card-title">Total Project Budgets</h5>
                                        <div class="d-flex align-items-center">
                                            <div
                                                class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                                <i class="bi bi-currency-dollar"></i>
                                            </div>
                                            <div class="ps-3">
                                                <h6>${{ number_format($profits->sum('project_budget'), 2) }}</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xxl-3 col-md-6">
                                <div class="card info-card customers-card">
                                    <div class="card-body">
                                        <h5 class="card-title">Total Expert Costs</h5>
                                        <div class="d-flex align-items-center">
                                            <div
                                                class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                                <i class="bi bi-people"></i>
                                            </div>
                                            <div class="ps-3">
                                                <h6>${{ number_format($profits->sum('expert_cost'), 2) }}</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xxl-3 col-md-6">
                                <div class="card info-card revenue-card">
                                    <div class="card-body">
                                        <h5 class="card-title">Total Net Profit</h5>
                                        <div class="d-flex align-items-center">
                                            <div
                                                class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                                <i class="bi bi-graph-up-arrow"></i>
                                            </div>
                                            <div class="ps-3">
                                                <h6>${{ number_format($profits->sum('net_profit'), 2) }}</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profits Table -->
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Profit Reports List</h5>
                        <div class="table-responsive">
                            <table class="table table-borderless table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center align-middle">ID</th>
                                        <th class="text-center align-middle">Project</th>
                                        <th class="text-center align-middle">Project Budget</th>
                                        <th class="text-center align-middle">Expert Cost</th>
                                        <th class="text-center align-middle">Payment Amount</th>
                                        <th class="text-center align-middle">{{ $profits->sum('profit_percentage') ? ( $profits->sum('profit_percentage') > 0 ? 'Net Profit' : 'Net Loss' ) : 'Net Profit' }}</th>
                                        <th class="text-center align-middle">Profit %</th>
                                        <th class="text-center align-middle">Status</th>
                                        <th class="text-center align-middle">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($profits as $profit)
                                        <tr>
                                            <td class="text-center align-middle">{{ $profit->id }}</td>
                                            <td class="text-center align-middle">{{ $profit->project->title }}</td>
                                            <td class="text-center align-middle">
                                                ${{ number_format($profit->project->budget, 2) }}</td>
                                            <td class="text-center align-middle">
                                                {{ $profit->expert_cost ? '$' . number_format($profit->expert_cost, 2) : 'N/A' }}</td>
                                            <td class="text-center align-middle">
                                                {{ $profit->payment ? '$' . number_format($profit->payment->amount, 2) : 'N/A' }}
                                            </td>
                                            <td
                                                class="text-center align-middle text-{{ $profit->profit >= 0 ? 'success' : 'danger' }}">
                                                ${{ number_format($profit->profit, 2) }}
                                            </td>
                                            <td
                                                class="text-center align-middle text-{{ $profit->profit_percentage >= 0 ? 'success' : 'danger' }}">
                                                {{ number_format($profit->profit_percentage, 2) }}%
                                            </td>
                                            <td class="text-center align-middle">
                                                <span class="badge bg-success">{{ strtoupper($profit->project->status) }}
                                                </span>
                                            </td>
                                            <td class="text-center align-middle">
                                                <div class="dropdown position-static">
                                                    <button class="btn btn-light btn-sm rounded-circle" type="button"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="bi bi-three-dots-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end" style="position: fixed;">
                                                        <li>
                                                            <a href="{{ route('profits.show', $profit->id) }}"
                                                                class="dropdown-item">
                                                                <i class="ri-eye-line"></i>Show
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <form action="{{ route('profits.delete', $profit->id) }}"
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
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">No profit reports found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
