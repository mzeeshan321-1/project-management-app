<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    @yield('title')
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="{{ asset('assets/img/favicon.png') }}" rel="icon">
    <link href="{{ asset('assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/quill/quill.snow.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/simple-datatables/style.css') }}" rel="stylesheet">

    {{-- jquery links --}}
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/resources/demos/style.css">

    <!-- Template Main CSS File -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
</head>

<body>
    @if (!empty(auth()->user()))
        @include('layouts.common.header')

        @include('layouts.common.sidebar')

        <main id="main" class="main">
            @yield('content')
        </main><!-- End #main -->

        @include('layouts.common.footer')
    @else
        <main>
            @yield('content')
        </main><!-- End #main -->
    @endif

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="{{ asset('assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/chart.js/chart.umd.js') }}"></script>
    <script src="{{ asset('assets/vendor/echarts/echarts.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/quill/quill.js') }}"></script>
    <script src="{{ asset('assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
    <script src="{{ asset('assets/vendor/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>

    <!-- Template Main JS File -->
    <script src="{{ asset('assets/js/main.js') }}"></script>

    {{-- jquery links --}}
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>
    @yield('script')

    <script>
        /**
         * Enhanced Theme Toggle Functionality with Flicker Prevention
         */
        
        // Immediately apply theme before DOM loads to prevent flicker
        (function() {
            const savedTheme = localStorage.getItem('theme') ||
                              (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
            document.documentElement.setAttribute('data-theme', savedTheme);
            // Add preload class to prevent transitions during initial load
            document.documentElement.classList.add('preload');
        })();

        $(function () {
            // Remove preload class after DOM is ready to enable transitions
            setTimeout(() => {
                $('html').removeClass('preload');
            }, 100);

            // Get saved theme from localStorage or system preference
            const getInitialTheme = () => {
                const savedTheme = localStorage.getItem('theme');
                if (savedTheme) {
                    return savedTheme;
                }
                return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            };

            let currentTheme = getInitialTheme();
            
            // Ensure theme is applied
            $('html').attr('data-theme', currentTheme);
            
            // Update toggle button icon based on current theme
            const updateToggleIcon = (theme) => {
                const toggleBtn = $('.theme-toggle i');
                if (toggleBtn.length) {
                    if (theme === 'dark') {
                        toggleBtn.removeClass('bi-moon-fill').addClass('bi-sun-fill');
                        $('.theme-toggle').attr('title', 'Switch to Light Mode');
                    } else {
                        toggleBtn.removeClass('bi-sun-fill').addClass('bi-moon-fill');
                        $('.theme-toggle').attr('title', 'Switch to Dark Mode');
                    }
                }
            };
            
            // Initialize icon
            updateToggleIcon(currentTheme);
            
            // Enhanced theme toggle function
            const toggleTheme = () => {
                const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                
                // Apply new theme immediately
                $('html').attr('data-theme', newTheme);
                currentTheme = newTheme;
                
                // Save to localStorage
                localStorage.setItem('theme', newTheme);
                
                // Update toggle button icon
                updateToggleIcon(newTheme);
                
                // Trigger custom event for other components that might need to respond
                $(document).trigger('themeChanged', [newTheme]);
                
                // Add subtle haptic feedback for mobile devices
                if ('vibrate' in navigator) {
                    navigator.vibrate(50);
                }
            };
            
            // Add event listener to theme toggle button with debouncing
            let toggleTimeout;
            $('.theme-toggle').on('click', function(e) {
                e.preventDefault();
                clearTimeout(toggleTimeout);
                toggleTimeout = setTimeout(toggleTheme, 50);
            });
            
            // Keyboard accessibility
            $('.theme-toggle').on('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    toggleTheme();
                }
            });
            
            // Listen for system theme changes
            const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
            const handleSystemThemeChange = (e) => {
                // Only auto-switch if user hasn't manually set a preference
                if (!localStorage.getItem('theme')) {
                    const systemTheme = e.matches ? 'dark' : 'light';
                    $('html').attr('data-theme', systemTheme);
                    currentTheme = systemTheme;
                    updateToggleIcon(systemTheme);
                    $(document).trigger('themeChanged', [systemTheme]);
                }
            };
            
            // Modern browsers
            if (mediaQuery.addEventListener) {
                mediaQuery.addEventListener('change', handleSystemThemeChange);
            } else {
                // Fallback for older browsers
                mediaQuery.addListener(handleSystemThemeChange);
            }
            
            // Handle page visibility changes to ensure theme consistency
            document.addEventListener('visibilitychange', function() {
                if (!document.hidden) {
                    const savedTheme = localStorage.getItem('theme') ||
                                     (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
                    if (savedTheme !== currentTheme) {
                        $('html').attr('data-theme', savedTheme);
                        currentTheme = savedTheme;
                        updateToggleIcon(savedTheme);
                    }
                }
            });
            
            // Handle storage changes from other tabs
            window.addEventListener('storage', function(e) {
                if (e.key === 'theme' && e.newValue !== currentTheme) {
                    $('html').attr('data-theme', e.newValue);
                    currentTheme = e.newValue;
                    updateToggleIcon(e.newValue);
                    $(document).trigger('themeChanged', [e.newValue]);
                }
            });
            
            // Expose theme functions globally for other scripts
            window.themeManager = {
                getCurrentTheme: () => currentTheme,
                setTheme: (theme) => {
                    if (theme === 'dark' || theme === 'light') {
                        $('html').attr('data-theme', theme);
                        currentTheme = theme;
                        localStorage.setItem('theme', theme);
                        updateToggleIcon(theme);
                        $(document).trigger('themeChanged', [theme]);
                    }
                },
                toggleTheme: toggleTheme
            };
            
            // Initialize any theme-dependent components
            $(document).trigger('themeReady', [currentTheme]);
        });

        //Sidebar toggle functionality
        $('.toggle-sidebar-btn').on('click', function(e) {
            e.preventDefault();
            $('body').toggleClass('toggle-sidebar');
        });
    </script>

</body>
</html>
