@extends('layouts.app')

@section('title')
    <title>Create Client Account</title>
@endsection

@section('content')
    <div class="pagetitle">
        <h1>Create Client Account</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('clients.index') }}">Clients</a></li>
                <li class="breadcrumb-item active">Create Client Account</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <div class="text-end mb-2" title="Back to Client">
        <a href="{{ route('clients.index') }}" class="btn btn-primary"><i class="ri-arrow-left-s-line"></i></a>
    </div>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Client Details</h5>
            <!-- Floating Labels Form -->
            <form method="post" action="{{ route('clients.store') }}" class="row g-3" enctype="multipart/form-data">
                @csrf
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="first_name" id="FirstName" value="{{ old('first_name') }}" placeholder="First Name"
                            required>
                        <label for="FirstName">First Name</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="last_name" value="{{ old('last_name') }}" id="LastName" placeholder="Last Name">
                        <label for="LastName">Last Name</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="email" class="form-control" name="email" id="Email" value="{{ old('email') }}" placeholder="Email"
                            required autocomplete="off">
                        <label for="Email">Email</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="text" name="industry" class="form-control" placeholder="Industry" id="Industry" required>
                        <label for="Industry">Industry</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="password" class="form-control" name="password" value="{{ old('password') }}" id="Password" placeholder="Password"
                            required autocomplete="off">
                        <label for="Password">Password</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password" id="password_confirmation" required autocomplete="new-password">
                        <label for="password_confirmation">Confirm Password</label>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-floating">
                        <textarea class="form-control" name="address" id="Address" placeholder="Address" style="height: 100px;" required>{{ old('address') }}</textarea>
                        <label for="Address">Address</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="text" class="form-control"value="{{ old('contact') }}" name="contact" id="Contact" placeholder="Contact Info"
                            required>
                        <label for="Contact">Contact Info</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <select class="form-select" name="status" id="Status" aria-label="Status" placeholder="Status" required>
                            <option selected value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="suspended" {{ old('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                        </select>
                        <label for="Status">Status</label>
                    </div>
                </div>
                <div class="col-md-12">
                    <input type="file" name="image" class="form-control" id="image" title="Image"
                        accept="image/*">
                </div>
                <div class="offset-md-4 col-md-4 mt-3">
                    <img src="" alt="Select Image" id="preview" class="img-thumbnail" style="display: none;">
                </div>
                <div class="text-center mt-5">
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
            });
        });
    </script>
@endsection
