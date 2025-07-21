@extends('layouts.app')
@section('title')
    <title>Account Register</title>
@endsection

@section('content')
    <div class="container">
        <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

                        <div class="d-flex justify-content-center py-4">
                            <a href="index.html" class="logo d-flex align-items-center w-auto">
                                <img src="assets/img/logo.png" alt="">
                                <span class="d-none d-lg-block">NiceAdmin</span>
                            </a>
                        </div><!-- End Logo -->

                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="py-2">
                                    <h5 class="card-title text-center pb-0 fs-4">Create an Account</h5>
                                </div>
                                <form method="POST" action="{{ route('register') }}" class="row g-3 needs-validation">
                                    @csrf
                                    <div class="col-12">
                                        <label for="firstName" class="form-label">Name</label>
                                        <input type="text" name="first_name" value="{{ old('first_name') }}"
                                            class="form-control @error('first_name') is-invalid @enderror" id="firstName"
                                            required autofocus>
                                        <small class="invalid-feedback" role="alert">Please enter your name</small>
                                    </div>

                                    <div class="col-12">
                                        <label for="Email" class="form-label">Email</label>
                                        <input type="email" name="email" value="{{ old('email') }}"
                                            class="form-control" id="Email" required>
                                        <small class="invalid-feedback" role="alert">Please enter your email</small>
                                    </div>

                                    <div class="col-12">
                                        <label for="Password" class="form-label">Password</label>
                                        <input type="password" name="password" class="form-control" id="Password" required>
                                        <small class="text-muted">Minimum 8 characters</small>
                                        <small class="invalid-feedback" role="alert">Please enter your Password</small>
                                    </div>

                                    <div class="col-12">
                                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                                        <input type="password" name="password_confirmation" class="form-control"
                                            id="password_confirmation" required autocomplete="new-password">

                                    </div>

                                    <div class="col-12">
                                        <input class="btn btn-primary mt-3 w-100" type="submit" value="Create Account">
                                    </div>
                                    <div class="col-12">
                                        <p class="small mb-0">Already have an account? <a
                                                href="{{ route('login') }}">Login</a></p>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
