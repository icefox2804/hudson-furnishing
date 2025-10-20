<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Admin Panel - Hudson Furnishing')</title>

    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <!-- Custom Admin CSS -->

    <!-- SweetAlent2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    @vite(['resources/css/app.css', 'resources/css/admin.css', 'resources/js/admin.js', 'resources/js/commonchat.js'])
    
    @stack('styles')
</head>
<body class="bg-light">
    <!-- Admin Sidebar -->
    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
                <div class="position-sticky pt-3">
                    <div class="text-center mb-4">
                        <img src="{{ asset('images/logo.png') }}" alt="Hudson Furnishing" height="40">
                        <h6 class="text-white mt-2">Quản Lý Website</h6>
                    </div>
                    
                    <ul class="nav flex-column">        
                        <li class="nav-item">
                            <a class="nav-link text-white {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" 
                               href="{{ route('admin.dashboard') }}">
                                <i class="fas fa-tachometer-alt me-2"></i>Bảng Điều Khiển
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white {{ request()->routeIs('admin.products.*') ? 'active' : '' }}" 
                               href="{{ route('admin.products.index') }}">
                                <i class="fas fa-box me-2"></i>Sản Phẩm
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" 
                               href="{{ route('admin.categories.index') }}">
                                <i class="fas fa-tags me-2"></i>Danh Mục
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white {{ request()->routeIs('admin.brands.*') ? 'active' : '' }}" 
                               href="{{ route('admin.brands.index') }}">
                                <i class="fas fa-star me-2"></i>Thương Hiệu
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white {{ request()->routeIs('admin.materials.*') ? 'active' : '' }}" 
                               href="{{ route('admin.materials.index') }}">
                                <i class="fas fa-cube me-2"></i>Vật Liệu
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white {{ request()->routeIs('admin.offers.*') ? 'active' : '' }}" 
                               href="{{ route('admin.offers.index') }}">
                                <i class="fas fa-tags me-2"></i>Ưu Đãi
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}" 
                               href="{{ route('admin.reviews.index') }}">
                                <i class="fas fa-star me-2"></i>Đánh Giá
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white {{ request()->routeIs('admin.gallery.*') ? 'active' : '' }}" 
                               href="{{ route('admin.gallery.index') }}">
                                <i class="fas fa-images me-2"></i>Thư Viện
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white {{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}" 
                               href="{{ route('admin.contacts.index') }}">
                                <i class="fas fa-envelope me-2"></i>Liên Hệ
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}" 
                               href="{{ route('admin.settings.index') }}">
                                <i class="fas fa-cog me-2"></i>Cài Đặt
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white {{ request()->routeIs('admin.trash.*') ? 'active' : '' }}" 
                               href="{{ route('admin.trash.index') }}">
                                <i class="fas fa-trash me-2"></i>Thùng Rác
                            </a>
                        </li>
                    </ul>
                    
                    <hr class="text-white">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{ route('home') }}">
                                <i class="fas fa-external-link-alt me-2"></i>Xem Trang Web
                            </a>
                        </li>
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-link nav-link text-white">
                                    <i class="fas fa-sign-out-alt me-2"></i>Đăng Xuất
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <!-- Top Navigation -->
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">@yield('page-title', 'Bảng Điều Khiển')</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        @yield('page-actions')
                    </div>
                </div>

                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Page Content -->
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom Admin JS -->
    <script src="{{ asset('js/admin.js') }}"></script>
    <!-- CKEditor 5 -->
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.0/classic/ckeditor.js"></script>
    
    @stack('scripts')
</body>
</html>
