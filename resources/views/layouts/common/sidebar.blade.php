<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">

        <!-- Main -->
        <li class="nav-heading">Main</li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dashboard') ? '' : 'collapsed' }}" href="{{ route('dashboard') }}">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <!-- Middleman/Tenants -->
        @can('view middleman')
            <li class="nav-heading">Tenant Management</li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('middleman.*') ? '' : 'collapsed' }}"
                    href="{{ route('middleman.index') }}">
                    <i class="bi bi-building"></i>
                    <span>Tenants</span>
                </a>
            </li>
        @endcan

        <!-- Experts -->
        @can('manage experts')
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('experts.*') ? '' : 'collapsed' }}"
                    href="{{ route('experts.index') }}">
                    <i class="bi bi-person-badge"></i>
                    <span>Experts</span>
                </a>
            </li>
        @endcan

        <!-- Clients -->
        @can('manage clients')
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('clients.*') ? '' : 'collapsed' }}"
                    href="{{ route('clients.index') }}">
                    <i class="bi bi-person-lines-fill"></i>
                    <span>Clients</span>
                </a>
            </li>
        @endcan

        <!-- Projects & Tasks -->
        @canany(['manage projects', 'view projects', 'assign projects', 'view tasks', 'manage tasks', 'request new
            projects', 'update project status'])
            <li class="nav-heading">Project Management</li>
        @endcanany

        @canany(['manage projects', 'view projects', 'request new projects', 'update project status'])
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('projects.*') ? '' : 'collapsed' }}"
                    href="{{ route('projects.index') }}">
                    <i class="bi bi-folder"></i>
                    <span>Projects</span>
                </a>
            </li>
        @endcanany

        @can('assign projects')
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('project_assignments.*') ? '' : 'collapsed' }}"
                    href="{{ route('project_assignments.index') }}">
                    <i class="bi bi-diagram-3"></i>
                    <span>Project Assignments</span>
                </a>
            </li>
        @endcan

        @canany(['view tasks', 'manage tasks'])
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('tasks.*') ? '' : 'collapsed' }}"
                    href="{{ route('tasks.index') }}">
                    <i class="bi bi-list-task"></i>
                    <span>Tasks</span>
                </a>
            </li>
        @endcanany

        <!-- Financial -->
        @canany(['view payments', 'manage payments', 'create payments', 'view expenses', 'manage expenses'])
            <li class="nav-heading">Financial</li>

            @canany(['view payments', 'manage payments', 'create payments'])
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('payments.*') ? '' : 'collapsed' }}"
                        href="{{ route('payments.index') }}">
                        <i class="bi bi-cash-stack"></i>
                        <span>Payments</span>
                    </a>
                </li>
            @endcanany

            @canany(['view reports', 'manage reports'])
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('profits.*') ? '' : 'collapsed' }}" href="{{ route('profits.index') }}">
                        <i class="bi bi-graph-up"></i>
                        <span>Profit Reports</span>
                    </a>
                </li>
            @endcanany
        @endcanany

        <!-- Documents -->
        @canany(['upload project deliverables', 'manage project deliverables', 'update project dileverables'])
            <li class="nav-heading">Documents</li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('files.*') ? '' : 'collapsed' }}" href="{{ route('files.index') }}">
                    <i class="bi bi-files"></i>
                    <span>File Manager</span>
                </a>
            </li>
        @endcanany

        <!-- Account -->
        <li class="nav-heading">Account</li>
        @can('view profile')
            <li class="nav-item">
                <a class="nav-link" href="">
                    <i class="bi bi-person"></i>
                    <span>Profile</span>
                </a>
            </li>
        @endcan

        <!-- Settings -->
        @canany(['view settings', 'manage settings'])
            <li class="nav-item">
                <a class="nav-link" href="">
                    <i class="bi bi-gear"></i>
                    <span>Settings</span>
                </a>
            </li>
        @endcanany
        <!-- User Management -->
        {{-- <li class="nav-heading">User Management</li>
        <li class="nav-item">
            <a class="nav-link" href="">
                <i class="bi bi-people"></i>
                <span>All Users</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="">
                <i class="bi bi-shield-lock"></i>
                <span>Roles</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="">
                <i class="bi bi-key"></i>
                <span>Permissions</span>
            </a>
        </li> --}}
    </ul>
</aside><!-- End Sidebar-->
