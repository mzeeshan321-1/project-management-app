@extends('layouts.app')
@section('title')
    <title>Login</title>
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

                        <div class="card mb-3">

                            <div class="card-body">

                                <div class="pt-4 pb-2">
                                    <h5 class="card-title text-center pb-0 fs-4">Login to Your Account</h5>
                                    <p class="text-center small">Enter your email & password to login</p>
                                </div>

                                @if (session('status'))
                                    <div class="alert alert-success mb-4" role="alert">
                                        {{ session('status') }}
                                    </div>
                                @endif

                                <form method="POST" action="{{ route('login') }}" class="row g-3 needs-validation"
                                    novalidate>
                                    @csrf
                                    <div class="col-12">
                                        <label for="Email" class="form-label">Email</label>
                                        <input type="email" name="email" value="{{ old('email') }}"
                                            class="form-control" id="Email" required autofocus autocomplete="email">
                                        <small class="invalid-feedback" role="alert">Please enter your email</small>
                                    </div>

                                    <div class="col-12">
                                        <label for="Password" class="form-label">Password</label>
                                        <input type="password" name="password" class="form-control" id="Password" required autocomplete="current-password">
                                        <small class="text-muted">Minimum 8 characters</small>
                                        <small class="invalid-feedback" role="alert">Please enter your Password</small>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="remember"
                                                id="remember_me">
                                            <label class="form-check-label" for="remember_me">Remember me</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        @if (Route::has('password.request'))
                                        <small class="text-dark text-sm">
                                            <a class="text-decoration-underline"
                                                href="{{ route('password.request') }}">
                                                Forgot your password?
                                            </a>
                                            </small>
                                        @endif
                                        <input class="btn btn-primary mt-3 w-100" type="submit" value="Login">
                                    </div>
                                    <div class="col-12">
                                        <p class="small mb-0">Don't have account? <a href="{{ route('register') }}">Create
                                                an
                                                account</a></p>
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
