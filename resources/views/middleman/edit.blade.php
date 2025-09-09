@extends('layouts.app')

@section('title')
    <title>Edit Tenant Account</title>
@endsection

@section('content')
    <div class="pagetitle">
        <h1>Edit Tenant Account</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('middleman.index') }}">Tenants</a></li>
                <li class="breadcrumb-item active">Edit Tenant Account</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <div class="text-end mb-2" title="Back to Tenants">
        <a href="{{ route('middleman.index') }}" class="btn btn-primary"><i class="ri-arrow-left-s-line"></i></a>
    </div>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Edit Tenant Details</h5>
            <!-- Floating Labels Form -->
            <form method="post" action="{{ route('middleman.update', $user->id) }}" class="row g-3"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="col-md-4">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="first_name" id="FirstName"
                            value="{{ $user->first_name }}" placeholder="First Name" required>
                        <label for="FirstName">First Name</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="last_name" value="{{ $user->last_name }}"
                            id="LastName" placeholder="Last Name">
                        <label for="LastName">Last Name</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating">
                        <input type="email" class="form-control" name="email" id="Email" value="{{ $user->email }}"
                            placeholder="Email" required autocomplete="off">
                        <label for="Email">Email</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="password" class="form-control" name="password" id="Password" placeholder="Password"
                            required autocomplete="off">
                        <label for="Password">Password</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="password" name="password_confirmation" class="form-control"
                            placeholder="Confirm Password" id="password_confirmation" required autocomplete="new-password">
                        <label for="password_confirmation">Confirm Password</label>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-floating">
                        <textarea class="form-control" name="address" id="Address" placeholder="Address" style="height: 100px;">{{ $user->address }}</textarea>
                        <label for="Address">Address</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="text" class="form-control"value="{{ $user->contact }}" name="contact" id="Contact"
                            placeholder="Contact Info">
                        <label for="Contact">Contact Info</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <select class="form-select" name="status" id="Status" aria-label="Status" placeholder="Status">
                            <option selected value="active" {{ $user->status == 'active' ? 'selected' : '' }}>Active
                            </option>
                            <option value="inactive" {{ $user->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="suspended" {{ $user->status == 'suspended' ? 'selected' : '' }}>Suspended
                            </option>
                        </select>
                        <label for="Status">Status</label>
                    </div>
                </div>
                <div class="col-md-12 mt-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="input-group mt-3">
                                <div class="form-file">
                                    <input type="file" name="image" id="image" accept="image/*"
                                        class="form-file-input" style="display: none;">
                                    <label class="form-file-label border rounded" for="image">
                                        <span class="form-file-button btn btn-light">Upload Image</span>
                                    </label>
                                </div>
                            </div>
                            @if (!empty($user->image))
                                <div class="d-flex justify-content-center mt-3">
                                    <img src="{{ asset('images/' . $user->image) }}" alt="{{ $user->first_name }}"
                                        class="img-thumbnail" id="preview">
                                </div>
                                <div id="image-name" class="text-center mt-2"></div>
                            @else
                                <div class="d-flex justify-content-center mt-3">
                                    <img src="" alt="Select Image" id="preview" class="img-thumbnail"
                                        style="display: none;">
                                </div>
                                <div id="image-name" class="text-center mt-2" style="display: none;"></div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="text-center mt-3">
                    <input type="Reset" value="Reset" class="btn btn-light">
                    <input type="submit" value="Submit" class="btn btn-primary">
                </div>
            </form><!-- End floating Labels Form -->
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#image').on('change', function(event) {
                const imageInput = event.target;
                const preview = $('#preview');
                const imageName = $('#image-name');

                if (imageInput.files && imageInput.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.attr('src', e.target.result);
                        preview.show();
                        imageName.text(imageInput.files[0].name).show();
                    };
                    reader.readAsDataURL(imageInput.files[0]);
                } else {
                    preview.attr('src', '');
                    preview.hide();
                    imageName.text('').hide();
                }
            });
        });
    </script>
@endsection
