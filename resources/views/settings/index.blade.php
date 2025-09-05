@extends('layouts.app')

@section('title')
    <title>Settings</title>
@endsection

@section('content')
    <div class="pagetitle">
        <h1>Settings</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Settings</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                
                @if (session('status') === 'profile-updated')
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        Profile updated successfully!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                @if (session('status') === 'password-updated')
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        Password updated successfully!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                @if (session('status') === 'notifications-updated')
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        Notification preferences updated successfully!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                @if (session('status') === 'settings-updated')
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        Settings updated successfully!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                <!-- Profile Settings Card -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Profile Settings</h5>
                        
                        <form method="POST" action="{{ route('settings.update') }}">
                            @csrf
                            <input type="hidden" name="update_profile" value="1">
                            
                            <div class="row mb-3">
                                <label for="first_name" class="col-md-4 col-lg-3 col-form-label">First Name</label>
                                <div class="col-md-8 col-lg-9">
                                    <input name="first_name" type="text" class="form-control" id="first_name" value="{{ old('first_name', $user->first_name) }}">
                                    @error('first_name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <label for="last_name" class="col-md-4 col-lg-3 col-form-label">Last Name</label>
                                <div class="col-md-8 col-lg-9">
                                    <input name="last_name" type="text" class="form-control" id="last_name" value="{{ old('last_name', $user->last_name) }}">
                                    @error('last_name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <label for="email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                                <div class="col-md-8 col-lg-9">
                                    <input name="email" type="email" class="form-control" id="email" value="{{ old('email', $user->email) }}">
                                    @error('email')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <label for="contact" class="col-md-4 col-lg-3 col-form-label">Contact Number</label>
                                <div class="col-md-8 col-lg-9">
                                    <input name="contact" type="text" class="form-control" id="contact" value="{{ old('contact', $user->contact) }}">
                                    @error('contact')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <label for="address" class="col-md-4 col-lg-3 col-form-label">Address</label>
                                <div class="col-md-8 col-lg-9">
                                    <input name="address" type="text" class="form-control" id="address" value="{{ old('address', $user->address) }}">
                                    @error('address')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Password Change Card -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Change Password</h5>
                        
                        <form method="POST" action="{{ route('settings.update') }}">
                            @csrf
                            <input type="hidden" name="update_password" value="1">
                            
                            <div class="row mb-3">
                                <label for="current_password" class="col-md-4 col-lg-3 col-form-label">Current Password</label>
                                <div class="col-md-8 col-lg-9">
                                    <input name="current_password" type="password" class="form-control" id="current_password">
                                    @if ($errors->updatePassword->has('current_password'))
                                        <div class="text-danger">{{ $errors->updatePassword->first('current_password') }}</div>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <label for="password" class="col-md-4 col-lg-3 col-form-label">New Password</label>
                                <div class="col-md-8 col-lg-9">
                                    <input name="password" type="password" class="form-control" id="password">
                                    @if ($errors->updatePassword->has('password'))
                                        <div class="text-danger">{{ $errors->updatePassword->first('password') }}</div>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <label for="password_confirmation" class="col-md-4 col-lg-3 col-form-label">Confirm New Password</label>
                                <div class="col-md-8 col-lg-9">
                                    <input name="password_confirmation" type="password" class="form-control" id="password_confirmation">
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Change Password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection