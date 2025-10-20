<header class="navbar navbar-expand-lg navbar-light align-items-center sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('home') }}">
            @php use Illuminate\Support\Facades\Storage; @endphp
            <img src="{{ isset($siteSettings['logo']) && $siteSettings['logo']
                 ? Storage::url($siteSettings['logo']) 
                 : asset('images/logo.png') }}"            
            alt="{{ $siteSettings['site_name'] ?? 'Hudson Furnishing' }}" height="40">
        </a>
        
        <button class="navbar-toggler border-0" 
            type="button" 
            data-bs-toggle="collapse" 
            data-bs-target="#navbarNav"
            aria-controls="navbarNav"
            aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                        Trang Chủ
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="productsDropdown" role="button" data-bs-toggle="dropdown">
                        Sản Phẩm
                    </a>
                    <ul class="dropdown-menu shadow">
                        <li>
                            <a class="dropdown-item" href="{{ route('products.index') }}">
                            Tất Cả Sản Phẩm
                            </a>
                        </li>
                        <li><hr class="dropdown-dividerdivider"></li>
                        @foreach ($sections as $section)
                        <li>
                            <a class="dropdown-item" href="{{ route('products.section', $section->slug)}}">
                                {{ $section->name}}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('gallery.*') ? 'active' : '' }}" href="{{ route('gallery.index') }}">
                        Thư Viện
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('offers.*') ? 'active' : '' }}" href="{{ route('offers.index') }}">
                        Khuyến Mãi
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">
                        Giới Thiệu
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('contact.*') ? 'active' : '' }}" href="{{ route('contact.index') }}">
                        Liên Hệ
                    </a>
                </li>
            </ul>
            
            <!-- Phần đăng nhập/đăng ký hoặc thông tin người dùng -->
            <ul class="navbar-nav flex-nowrap">
                @auth                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle px-2" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user"></i>
                            <span class="d-none d-md-inline ms-1">{{ Str::limit(auth()->user()->name, 10) }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow">
                            <li><h6 class="dropdown-header">{{ auth()->user()->name }}</h6></li>
                            <li><hr class="dropdown-divider"></li>
                            @if(auth()->user()->isAdmin())
                                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                    <i class="fas fa-cog me-2"></i>Quản Trị
                                </a></li>
                                @else
                                <li>
                                    <a class="dropdown-item" href="{{ route('user.dashboard') }}">
                                        <i class="fas fa-user-circle me-2"></i>Dashboard của tôi
                                    </a>
                                </li>
                            @endif
                            <a class="dropdown-item" href="{{ route('password.edit') }}">
                                <i class="fas fa-key fa-sm fa-fw mr-2 text-gray-400"></i>
                                Đổi mật khẩu
                            </a>
                            <div class="dropdown-divider"></div>

                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-sign-out-alt me-2"></i>Đăng Xuất
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                    @else
                    <div class="nav-item d-flex align-items-center gap-1">                                                                    
                        <a class="btn btn-outline-secondary register-login" href="{{ route('login') }}">
                             Đăng Nhập
                        </a>
                        <a class="btn btn-outline-secondary register-login" href="{{ route('register') }}">
                             Đăng ký 
                        </a>                        
                    </div>
                @endauth
            </ul>
        </div>
    </div>
<style>
    /* style link giống input */
.navbar .nav-link.register-login {
    display: inline-flex;          /* để icon và text thẳng hàng */
    align-items: center;
    padding: 0.375rem 0.75rem;     /* giống input bootstrap */
    border: 2px solid #ced4da;     /* viền input */
    border-radius: 0.375rem;       /* bo góc như input */
    background-color: #fff;         /* nền trắng */
    color: #495057;                /* màu chữ giống input */
    font-size: 1rem;
    text-decoration: none;         /* bỏ gạch chân */
    transition: border-color 0.2s, box-shadow 0.2s;
}

.navbar .nav-link.register-login:hover {
    border-color: #0d6efd;         /* viền khi hover */
    box-shadow: 0 0 0 0.2rem rgba(13,110,253,.25); /* hiệu ứng outline giống input khi focus */
    color: #0d6efd;
}

@media (max-width: 991.98px) { /* tương ứng với breakpoint lg */
    .navbar .container {
        display: flex;
        justify-content: space-between;
        
    }

    .navbar-brand,
    .navbar-toggler {
        align-self: center;
    }

    .navbar-collapse {
        width: 100%;
    }
}

</style>
</header>
