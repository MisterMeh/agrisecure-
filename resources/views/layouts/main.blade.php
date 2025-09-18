<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'AgriSecure Admin')</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/custom-css/main.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    @livewireStyles
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

    <nav class="main-header navbar navbar-expand navbar-white navbar-light" style="height: 70px; background-color: #def5e2;">
        <ul class="navbar-nav ">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>

        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                @if(auth()->user()->profile_photo_path)
                    <img class="img-circle img-xl mr-2" width="45px" height="45px" src="{{ asset('storage/' . auth()->user()->profile_photo_path) }}" alt="User Image">
                @else
                    @php
                        $name = auth()->user()->name;
                        $words = preg_split('/\s+/', $name, -1, PREG_SPLIT_NO_EMPTY);
                        if(count($words) >= 2) {
                            $initials = strtoupper(mb_substr($words[0], 0, 1) . mb_substr($words[1], 0, 1));
                        } else {
                            $initials = strtoupper(mb_substr($name, 0, 2));
                        }
                    @endphp
                    <img class="img-circle img-xl mr-2" width="45px" height="45px" src="https://placehold.co/40x40/838584/ffffff?text={{ $initials }}" alt="User Image">
                @endif
            </li>
            @if (auth()->user()->role > 0)
                <li class="nav-item mr-4">
                    <div class="d-flex flex-column align-items-start">
                        <span style="font-size: 1rem;">{{ auth()->user()->name }}</span>
                        <span style="font-size: 0.85rem; color: #6c757d;">{{ auth()->user()->role == 0 ? 'Employee' : 'Admin' }}</span>
                    </div>
                </li>
            @endif
            
            {{-- <li class="nav-item mr-3 d-flex align-items-center">
                <a class="nav-link position-relative" href="#">
                    <i class="fas fa-bell text-dark"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.7rem;">
                        0
                    </span>
                </a>
            </li> --}}
            <li class="nav-item text-lg">
                <a class="nav-link" href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt text-danger"></i>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>
        </ul>
    </nav>
    <aside class="main-sidebar sidebar-dark-primary elevation-4">

    <div class="col-12 justify-content-center text-center mt-2" style="margin-left: -10px">
        <img src="{{ asset('images/logo.jpg') }}" alt="AgriSecure Logo"
        class="ml-3 text-dark"
        style="opacity: .8; width:60%;">
    </div>
    

<div class="sidebar">
    <nav class="mt-3">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            @if(auth()->user()->role > 0)
                
            <li class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link {{ (Request::is('*dashboard*') ? 'active' : '') }}">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>Dashboard</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('user_management') }}" class="nav-link {{ (Request::is('*user-management*') ? 'active' : '') }}">
                    <i class="nav-icon fas fa-users"></i>
                    <p>User Management</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin-file') }}" class="nav-link {{ (Request::is('*admin-file-management*') ? 'active' : '') }}">
                    <i class="nav-icon fas fa-folder-open"></i>
                    <p>File Management</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('audit-logs') }}" class="nav-link {{ (Request::is('*audit-logs*') ? 'active' : '') }}">
                    <i class="nav-icon fas fa-clipboard-list"></i>
                    <p>Audit Logs</p>
                </a>
            </li>
            {{-- <li class="nav-item">
                <a href="" class="nav-link {{ (Request::is('*report*') ? 'active' : '') }}">
                    <i class="nav-icon fas fa-chart-pie"></i>
                    <p>Reports</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="" class="nav-link {{ (Request::is('*terms-conditions*') ? 'active' : '') }}">
                    <i class="nav-icon fas fa-file-contract"></i>
                    <p>Terms & Conditions</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="" class="nav-link {{ (Request::is('*settings*') ? 'active' : '') }}">
                    <i class="nav-icon fas fa-cogs"></i>
                    <p>Settings</p>
                </a>
            </li> --}}

            @else
            <li class="nav-item">
                <a href="{{ route('admin-file') }}" class="nav-link {{ (Request::is('*file-management*') ? 'active' : '') }}">
                    <i class="nav-icon fas fa-folder-open"></i>
                    <p>File Management</p>
                </a>
            </li>
            @endif

        </ul>
    </nav>
</div>
</aside>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 style="color: #504f4f;">@yield('page_title', 'Page Title')</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            @yield('content')
        </div>
    </section>
</div>
{{-- <footer class="main-footer">
    <div class="float-right d-none d-sm-block">
        <b>Version</b> 1.0.0
    </div>
    <strong>Copyright &copy; 2024-{{ date('Y') }} <a href="#">AgriSecure</a>.</strong> All rights reserved.
</footer> --}}


<script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
@livewireScripts
</body>
</html>