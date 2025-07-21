<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">

        <!-- Main Dashboard -->
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dashboard') ? '' : 'collapsed' }}" href="{{ route('dashboard') }}">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li>

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

        <!-- Middleman/Tenants -->
        @can('view middleman')
            <li class="nav-heading">Tenant Management</li>
            <li class="nav-item">
                <a class="nav-link" href="">
                    <i class="bi bi-building"></i>
                    <span>Tenants</span>
                </a>
            </li>
        @endcan


        <!-- Experts -->
        @can('manage experts')
            <li class="nav-item">
                <a class="nav-link" href="">
                    <i class="bi bi-person-badge"></i>
                    <span>Experts</span>
                </a>
            </li>
        @endcan

        <!-- Clients -->
        @can('manage clients')
            <li class="nav-item">
                <a class="nav-link" href="">
                    <i class="bi bi-person-lines-fill"></i>
                    <span>Clients</span>
                </a>
            </li>
        @endcan

        <!-- Projects & Tasks -->
        <li class="nav-heading">Project Operations</li>
        <li class="nav-item">
            <a class="nav-link" href="">
                <i class="bi bi-folder"></i>
                <span>Projects</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="">
                <i class="bi bi-diagram-3"></i>
                <span>Project Assignments</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="">
                <i class="bi bi-list-task"></i>
                <span>Tasks</span>
            </a>
        </li>

        <!-- Financial -->
        <li class="nav-heading">Financial</li>
        <li class="nav-item">
            <a class="nav-link" href="">
                <i class="bi bi-cash-stack"></i>
                <span>Payments</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="">
                <i class="bi bi-graph-down"></i>
                <span>Expenses</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="">
                <i class="bi bi-graph-up"></i>
                <span>Profit Reports</span>
            </a>
        </li>

        <!-- Documents -->
        <li class="nav-heading">Documents</li>
        <li class="nav-item">
            <a class="nav-link" href="">
                <i class="bi bi-files"></i>
                <span>File Manager</span>
            </a>
        </li>

        <!-- Account -->
        <li class="nav-heading">Account</li>
        <li class="nav-item">
            <a class="nav-link" href="">
                <i class="bi bi-person"></i>
                <span>Profile</span>
            </a>
        </li>
    </ul>
</aside><!-- End Sidebar-->
