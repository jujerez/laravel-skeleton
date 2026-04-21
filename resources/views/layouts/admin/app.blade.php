<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name'))</title>

    <link rel="shortcut icon" href="{{ asset('assets/static/img/icons/icon-48x48.png') }}" />
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="{{ asset('assets/site/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/datatables-1.13.1/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/sweetalert-7.0.5/sweetalert2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/static/css/app.css') }}" rel="stylesheet">
    @stack('styles')
</head>

<body>
    <div class="wrapper">

        {{-- Sidebar --}}
        <nav id="sidebar" class="sidebar js-sidebar">
            <div class="sidebar-content js-simplebar">
                <a class="sidebar-brand" href="{{ Route::has('dashboard') ? route('dashboard') : url('/') }}">
                    <span class="align-middle">{{ config('app.name') }}</span>
                </a>
                <ul class="sidebar-nav">
                    @include('layouts.admin._sidebar')
                </ul>
            </div>
        </nav>

        <div class="main">

            {{-- Top Navbar --}}
            <nav class="navbar navbar-expand navbar-light navbar-bg">
                <a class="sidebar-toggle js-sidebar-toggle">
                    <i class="hamburger align-self-center"></i>
                </a>

                <div class="navbar-collapse collapse">
                    <ul class="navbar-nav navbar-align">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#" data-bs-toggle="dropdown">
                                <img src="{{ isset($user) ? $user->avatarUrl() : asset('assets/static/img/avatars/avatar.jpg') }}" class="avatar img-fluid rounded me-1" alt="{{ auth()->user()?->name }}" />
                                <span class="text-dark">{{ auth()->user()?->name }}</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <div class="dropdown-divider"></div>
                                <form method="POST" action="{{ Route::has('logout') ? route('logout') : url('/logout') }}" data-ajax="false">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="align-middle me-1" data-feather="log-out"></i> Log out
                                    </button>
                                </form>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>

            {{-- Page Content --}}
            <main class="content">
                <div class="container-fluid p-0">
                    @yield('content')
                </div>
            </main>

            {{-- Footer --}}
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row text-muted">
                        <div class="col-6 text-start">
                            <p class="mb-0">
                                <strong>{{ config('app.name') }}</strong> &copy; {{ date('Y') }}
                            </p>
                        </div>
                    </div>
                </div>
            </footer>

        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
    <script src="{{ asset('assets/static/js/app.js') }}"></script>
    <script src="{{ asset('assets/vendor/jquery.form/jquery.form.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/sweetalert-7.0.5/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('assets/js/ajax-form.js') }}"></script>
    <script src="{{ asset('assets/js/site.js') }}"></script>
    <script src="{{ asset('assets/vendor/datatables-1.13.1/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/datatables-1.13.1/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/js/layout.admin.js') }}"></script>

    @stack('scripts')
</body>

</html>
