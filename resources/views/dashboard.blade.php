@extends('layouts.app')

@section('title')
    @role('super-admin') <title>Dashboard - Super Admin</title> @endrole
    @role('middleman') <title>Dashboard - Tanent</title> @endrole
    @role('expert') <title>Dashboard - Expert</title> @endrole
    @role('client') <title>Dashboard - Client</title> @endrole
@endsection

@section('content')
        <div class="pagetitle">
            <h1>Dashboard</h1>
            @role('super-admin')
            <p>Welcome to the Super Admin Dashboard</p>
            @endrole
            @role('middleman')
            <p>Welcome to the Middleman Dashboard</p>
            @endrole
            @role('client')
                @if(auth()->user()->first_name == 'Client User 1')
                    <p>Welcome to the Client User 1 Dashboard</p>
                @elseif(auth()->user()->first_name == 'Client User 2')
                    <p>Welcome to the Client User 2 Dashboard</p>
                @else
                    <p>Welcome to the Client Dashboard</p>
                @endif
            @endrole
             @role('expert')
            <p>Welcome to the Expert Dashboard</p>
            @endrole
        </div><!-- End Page Title -->
@endsection
