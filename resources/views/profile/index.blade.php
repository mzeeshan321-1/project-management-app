@extends('layouts.app')

@section('title')
    <title>Profile</title>
@endsection

@section('content')
    <div class="pagetitle">
        <h1>Profile</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Profile</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    {{-- {{ dd($authUser) }} --}}
    <section class="section profile">
        <div class="row">
            <div class="col-xl-4">

                <div class="card">
                    <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                        <div class="d-flex align-items-center justify-content-between">
                            @if ($authUser->image)
                                <img src="{{ 'image/' . $authUser->image ?? '' }}" class="rounded-circle"
                                    style="width: 80%; height: 80%;">
                            @else
                                <div class="rounded-circle d-flex justify-content-center align-items-center me-2"
                                    style="width: 60px; height: 60px; font-size: 1.5em; background-color: #e6e5e5; color: #515050;">
                                    <span class="fs-5 fw-bold">
                                        {{ strtoupper(substr($authUser->first_name, 0, 1)) }}</span>
                                </div>
                            @endif
                        </div>
                        <h2 class="mt-3">{{ $authUser->first_name }}</h2>
                        <h3>{{ ucfirst($authUser->getRoleNames()->first()) }}</h3>
                    </div>
                </div>

            </div>

            <div class="col-xl-8">

                <div class="card">
                    <div class="card-body pt-3">
                        <!-- Bordered Tabs -->
                        <ul class="nav nav-tabs nav-tabs-bordered">
                            <li class="nav-item">
                                <button class="nav-link active" data-bs-toggle="tab"
                                    data-bs-target="#profile-overview">Overview</button>
                            </li>
                        </ul>
                        <div class="tab-content pt-2">
                            <div class="tab-pane fade show active profile-overview" id="profile-overview">
                                <h5 class="card-title">Profile Details</h5>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Full Name</div>
                                    <div class="col-lg-9 col-md-8">{{ $authUser->first_name }} {{ $authUser->last_name }}</div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Email</div>
                                    <div class="col-lg-9 col-md-8">{{ $authUser->email }}</div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Address</div>
                                    <div class="col-lg-9 col-md-8">{{ $authUser->address ?? 'N/A' }}</div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Contact Info</div>
                                    <div class="col-lg-9 col-md-8">{{ $authUser->contact ?? 'N/A' }}</div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Last Login</div>
                                    <div class="col-lg-9 col-md-8">
                                        {{ $authUser->last_login ? \Carbon\Carbon::parse($authUser->last_login)->diffForHumans() : 'N/A' }}
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Status</div>
                                    @if ($authUser->status == 'active')
                                    <div class="col-lg-9 col-md-8"><span class="badge bg-success">{{ strtoupper($authUser->status) }}</span></div>
                                    @elseif ($authUser->status == 'inactive')
                                    <div class="col-lg-9 col-md-8"><span class="badge bg-warning">{{ strtoupper($authUser->status) }}</span></div>
                                    @endif
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Created At</div>
                                    <div class="col-lg-9 col-md-8">{{ $authUser->created_at->format('M d, Y') }}</div>
                                </div>
                            </div>
                        </div><!-- End Bordered Tabs -->
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

