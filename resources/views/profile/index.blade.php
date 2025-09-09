@extends('layouts.app')

@section('title')
    <title>Profile</title>

    <!-- Extra CSS -->
    <style>
        .profile-wrapper {
            position: relative;
            display: inline-block;
        }

        .profile-img {
            border-radius: 50%;
            display: block;
        }

        .camera-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            opacity: 0;
            transition: opacity 0.3s ease;
            cursor: pointer;
            font-size: 24px;
            z-index: 10;
        }

        /* Show overlay only on hover */
        .profile-wrapper:hover .camera-overlay {
            opacity: 1;
        }
    </style>
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
                        <div class="position-relative profile-wrapper d-inline-block">
                            @if ($authUser->image)
                                <img src="{{ 'images/' . $authUser->image }}" class="rounded-circle profile-img">
                            @else
                                <div class="rounded-circle d-flex justify-content-center align-items-center me-2 profile-img"
                                    style="width: 60px; height: 60px; font-size: 2.0em; background-color: #e6e5e5; color: #515050;">
                                    <span>{{ strtoupper(substr($authUser->first_name, 0, 1)) }}</span>
                                </div>
                            @endif

                            <!-- Hoverable Camera Icon -->
                            <label for="image"
                                class="camera-overlay d-flex justify-content-center align-items-center rounded-circle">
                                <span class="d-flex justify-content-center align-items-center"
                                    style="width: 50%; height: 50%;">
                                    <i class="bi bi-camera text-white" style="font-size: 2.0em;"></i>
                                </span>
                            </label>
                            <form action="{{ route('profile.updateImage', $authUser->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <input type="file" id="image" name="image" class="d-none" accept="image/*">
                                <button type="submit" class="d-none"></button>
                            </form>
                        </div>

                        <h2 class="mt-3">{{ $authUser->first_name }}</h2>
                        {{ auth()->user()->hasRole('middleman') ? ' TENANT ' : strtoupper(auth()->user()->getRoleNames()->first()) }}
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
                                    <div class="col-lg-9 col-md-8">{{ $authUser->first_name }} {{ $authUser->last_name }}
                                    </div>
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
                                        <div class="col-lg-9 col-md-8"><span
                                                class="badge bg-success">{{ strtoupper($authUser->status) }}</span></div>
                                    @elseif ($authUser->status == 'inactive')
                                        <div class="col-lg-9 col-md-8"><span
                                                class="badge bg-warning">{{ strtoupper($authUser->status) }}</span></div>
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

@section('script')
    <script>
        $(document).ready(function() {
            $('#image').on('change', function(event) {
                const imageInput = event.target;
                const preview = $('#preview');

                if (imageInput.files && imageInput.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.attr('src', e.target.result);
                        preview.show();
                    };
                    reader.readAsDataURL(imageInput.files[0]);
                } else {
                    preview.attr('src', '');
                    preview.hide();
                }

                // Run the update image route
                $(this).closest('form').submit();
            });
        });
    </script>
@endsection


