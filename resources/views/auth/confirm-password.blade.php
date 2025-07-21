@extends('layouts.app')
@section('title')
    <title>confirm Password</title>
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
                                    <small class="text-center text-muted">This is a secure area of the application. Please confirm your password before continuing.</small>
                                </div>

                                <form method="POST" action="{{ route('password.confirm') }}" class="row g-3 needs-validation">
                                    @csrf
                                    <div class="col-12">
                                        <label for="Password" class="form-label">Password</label>
                                        <input type="password" name="password" class="form-control" id="Password" required autocomplete="current-password">
                                        <small class="text-muted">Minimum 8 characters</small>
                                        <small class="invalid-feedback" role="alert">Please enter your Password</small>
                                    </div>

                                    <div class="col-12">
                                        <input class="btn btn-primary mt-3 w-100" type="submit" value="Confirm">
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

