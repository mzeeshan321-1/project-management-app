@php
    $initials = strtoupper(substr(auth()->user()->first_name, 0, 1));
    $colors = ['#f94144', '#f3722c', '#f8961e', '#f9c74f', '#90be6d', '#43aa8b', '#577590'];
    $colorIndex = (int) substr(md5(auth()->user()->id), 0, 1) % count($colors);
    $randomColor = $colors[$colorIndex];
@endphp
<!-- ======= Header ======= -->
<header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
        <a href="{{ route('dashboard') }}" class="logo d-flex align-items-center">
            <img src="{{ asset('assets/img/logo.png') }}" alt="">
            <span class="d-none d-lg-block text-nowrap">
                {{ auth()->user()->hasRole('middleman') ? ' TENANT ' : strtoupper(auth()->user()->getRoleNames()->first()) }}
            </span>
        </a>
        <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

    <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center">
            <li class="nav-item">
                <button class="theme-toggle nav-icon" aria-label="Toggle between light and dark theme" type="button">
                    <i class="bi bi-moon-fill"></i>
                </button>
            </li>

            <li class="nav-item dropdown pe-3">

                <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                    @if ((auth()->user()->image ?? '') != null)
                        <img src="{{ asset('images/' . (auth()->user()->image ?? '')) }}" alt="Profile"
                            class="rounded-circle" width="40" height="40">
                    @else
                        <div class="rounded-circle d-flex justify-content-center align-items-center"
                            style="width: 40px; height: 40px; font-size: 1.5em; background-color: {{ $randomColor }}; color: #fff;">
                            {{ $initials }}
                        </div>
                    @endif
                    <div style="display: flex; flex-direction: column;">
                        <h6
                            class="d-none d-md-block dropdown-toggle ps-2 m-0 text-black">{{ auth()->user()->first_name . ' ' . auth()->user()->last_name }}</h6>
                        <small class="d-none d-md-block text-muted ps-2" style="font-size: 0.7em;">
                            {{ auth()->user()->hasRole('middleman') ? 'TENANT' : strtoupper(auth()->user()->getRoleNames()->first()) }}
                        </small>
                    </div>
                </a><!-- End Profile Iamge Icon -->

                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                    <li class="dropdown-header p-3">
                        @if ((auth()->user()->image ?? '') != null)
                            <img src="{{ asset('images/' . (auth()->user()->image ?? '')) }}" alt="Profile"
                                class="rounded-circle" width="100" height="100">
                        @else
                            <i class="bi bi-person-circle" style="font-size: 5em; color: {{ $randomColor }};"></i>
                        @endif
                        <h6 class="mt-3">{{ auth()->user()->first_name . ' ' . auth()->user()->last_name }}</h6>
                        <small class="text-muted d-block d-md-none">
                            {{ auth()->user()->hasRole('middleman') ? 'TENANT' : strtoupper(auth()->user()->getRoleNames()->first()) }}
                        </small>
                        <span>{{ auth()->user()->email }}</span>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('logout') }}">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Log Out</span>
                        </a>
                    </li>

                </ul><!-- End Profile Dropdown Items -->
            </li><!-- End Profile Nav -->

        </ul>
    </nav><!-- End Icons Navigation -->

</header><!-- End Header -->

<style>
    [data-theme="dark"] #header.header {
        background: #1e293b !important;
        border-bottom: 1px solid #334155 !important;
        color: #e2e8f0 !important;
    }
    [data-theme="dark"] #header .logo span {
        color: #e2e8f0 !important;
    }
    [data-theme="dark"] #header .nav-profile h6,
    [data-theme="dark"] #header .nav-profile span {
        color: #e2e8f0 !important;
    }
    [data-theme="dark"] #header .nav-profile .dropdown-menu.profile {
        background: #1e293b !important;
        border: 1px solid #334155 !important;
    }
    [data-theme="dark"] #header .nav-profile .dropdown-header {
        background: #334155 !important;
        color: #e2e8f0 !important;
    }
    [data-theme="dark"] #header .nav-profile .dropdown-item {
        color: #e2e8f0 !important;
    }
    [data-theme="dark"] #header .nav-profile .dropdown-item:hover {
        background: #334155 !important;
        color: #60a5fa !important;
    }
</style>
