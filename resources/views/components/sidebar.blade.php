@extends('layouts.app')

    @section('sidebar')
        <nav class="sidebar navbar navbar-vertical navbar-expand-xl navbar-light">
            <div class="sidebar-inner">
                <div class="sidebar-brand d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-speedometer2 me-3"></i>
                        <span class="sidebar-text">Dashboard</span>
                    </div>
                </div>

                <hr class="sidebar-divider my-0">

                <!-- Dashboard link for all authenticated users -->
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('dashboard') }}">
                        <i class="bi bi-speedometer2"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <!-- Middlemen link for users with manage middleman permission -->
                @can('manage middleman')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('middleman.index') }}">
                            <i class="bi bi-people"></i>
                            <span>Middlemen</span>
                        </a>
                    </li>
                @endcan

                <!-- Experts link for users with manage experts permission -->
                @can('manage experts')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('experts.index') }}">
                            <i class="bi bi-people"></i>
                            <span>Experts</span>
                        </a>
                    </li>
                @endcan

                <!-- Clients link for users with manage clients permission -->
                @can('manage clients')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('clients.index') }}">
                            <i class="bi bi-people"></i>
                            <span>Clients</span>
                        </a>
                    </li>
                @endcan

                <!-- Projects link for users with project permissions -->
                @canany(['manage projects', 'view projects', 'request new projects'])
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('projects.index') }}">
                            <i class="bi bi-folder"></i>
                            <span>Projects</span>
                        </a>
                    </li>
                @endcanany

                <!-- Tasks link for users with task permissions -->
                @canany(['manage tasks', 'view tasks'])
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('tasks.index') }}">
                            <i class="bi bi-list-task"></i>
                            <span>Tasks</span>
                        </a>
                    </li>
                @endcanany

                <!-- Payments link for users with payment permissions -->
                @canany(['manage payments', 'view payments', 'create payments'])
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('payments.index') }}">
                            <i class="bi bi-cash-stack"></i>
                            <span>Payments</span>
                        </a>
                    </li>
                @endcanany

                <!-- Files link for users with file permissions -->
                @canany(['manage project deliverables', 'upload project deliverables', 'update project deliverables'])
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('files.index') }}">
                            <i class="bi bi-files"></i>
                            <span>Files</span>
                        </a>
                    </li>
                @endcanany

                <!-- Project Assignments link for users with assignment permissions -->
                @can('assign projects')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('project_assignments.index') }}">
                            <i class="bi bi-diagram-3"></i>
                            <span>Assignments</span>
                        </a>
                    </li>
                @endcan

                <!-- Profit Reports link for users with report permissions -->
                @canany(['manage reports', 'view reports'])
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('profits.index') }}">
                            <i class="bi bi-graph-up"></i>
                            <span>Profit Reports</span>
                        </a>
                    </li>
                @endcanany

                <div class="sidebar-divider d-none d-xl-block"></div>
            </div>
        </nav>
    @endsection