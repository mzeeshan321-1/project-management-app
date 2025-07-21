@extends('layouts.app')
@section('title')
    <title>Forgot Password</title>
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
                                    <small class="text-center text-muted">Forgot your password? No problem. Just let us know
                                        your email address and we will email you a password reset link that will allow you
                                        to choose a new one.</small>
                                </div>

                                @if (session('status'))
                                    <div class="alert alert-success mb-4" role="alert">
                                        {{ session('status') }}
                                    </div>
                                @endif

                                <form method="POST" action="{{ route('password.email') }}" class="row g-3 needs-validation">
                                    @csrf
                                    <div class="col-12">
                                        <label for="Email" class="form-label">Email</label>
                                        <input type="email" name="email" value="{{ old('email') }}"
                                            class="form-control" id="Email" required autofocus autocomplete="email">
                                        <small class="invalid-feedback" role="alert">Please enter your email</small>
                                    </div>

                                    <div class="col-12">
                                        <input class="btn btn-primary mt-3 w-100" type="submit"
                                            value="Email Password Reset Link">
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
