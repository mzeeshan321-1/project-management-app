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

                        <a href="{{ route('dashboard') }}" class="mb-3">
                                <img src="{{ asset('assets/img/logo.png') }}" class="light-logo" style="width:200px;">
                                <img src="{{ asset('assets/img/logo-light.png') }}" class="dark-logo" style="width:200px;">
                        </a>

                        <div class="card mb-1">
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

                                <form method="POST" action="{{ route('login') }}" class="row g-3 needs-validation">
                                    @csrf
                                    <div class="col-12">
                                        <label for="Email" class="form-label">Email</label>
                                        <input type="email" name="email" value="{{ old('email') }}"
                                            class="form-control" id="Email" required autofocus autocomplete="email">
                                    </div>

                                    <div class="col-12">
                                        <label for="Password" class="form-label">Password</label>
                                        <input type="password" name="password" class="form-control" id="Password" required autocomplete="current-password">
                                        <small class="text-muted">Minimum 8 characters</small>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-check">
                                            <input class="form-check-input text-muted" type="checkbox" name="remember"
                                                id="remember_me">
                                            <label class="form-check-label text-muted" for="remember_me">Remember me</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        {{-- @if (Route::has('password.request'))
                                        <small class="text-dark text-sm">
                                            <a class="text-decoration-underline"
                                                href="{{ route('password.request') }}">
                                                Forgot your password?
                                            </a>
                                            </small>
                                        @endif --}}
                                        <input class="btn btn-primary mt-1 w-100" type="submit" value="Login">
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
