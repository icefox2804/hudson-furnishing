@extends('layouts.app')

@section('title', 'Đăng Ký - Hudson Furnishing')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-sm">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <img src="{{ isset($siteSettings['logo']) && $siteSettings['logo']
                                ? Storage::url($siteSettings['logo'])
                                : asset('images/logo.png') }}"
                                alt="Hudson Furnishing" height="40" class="mb-3">
                            <h3>Đăng Ký Tài Khoản</h3>
                            <p class="text-muted">Tạo tài khoản để trải nghiệm tốt hơn</p>
                        </div>

                        <form method="POST" action="{{ route('register') }}" novalidate>
                            @csrf

                            <!-- Họ tên -->
                            <div class="mb-3">

                                <label for="name" class="form-label">Họ và Tên</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name') }}"
                                    placeholder="Nhập họ và tên của bạn">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Tối thiểu 3 ký tự, không được toàn là số</div>
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email') }}"
                                    placeholder="Nhập địa chỉ email">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Mật khẩu -->
                            <div class="mb-3 position-relative">
                                <label for="password" class="form-label">Mật khẩu</label>
                                <input type="password" class="form-control pe-5 @error('password') is-invalid @enderror"
                                    id="password" name="password" placeholder="Nhập mật khẩu">
                                <button type="button"
                                    class="btn position-absolute top-50 end-0 translate-middle-y border-0 bg-transparent px-3 mt-1"
                                    id="togglePassword" tabindex="-1">
                                    <i class="fas fa-eye" id="togglePasswordIcon"></i>
                                </button>
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Mật khẩu phải có ít nhất 8 ký tự, bao gồm chữ cái và ký tự đặc biệt,
                                    không được chứa khoảng trắng</div>
                            </div>

                            <!-- Xác nhận mật khẩu -->
                            <div class="mb-3 position-relative">
                                <label for="password_confirmation" class="form-label">Xác Nhận Mật Khẩu</label>
                                <input type="password" class="form-control pe-5" id="password_confirmation"
                                    name="password_confirmation" placeholder="Nhập lại mật khẩu">

                                <button type="button"
                                    class="btn position-absolute top-50 end-0 translate-middle-y border-0 bg-transparent px-3 mt-3"
                                    id="togglePasswordConfirm" tabindex="-1">
                                    <i class="fas fa-eye" id="togglePasswordConfirmIcon"></i>
                                </button>
                            </div>

                            <!-- Nút đăng ký -->
                            <button type="submit" class="btn btn-primary w-100 mb-3">Đăng Ký</button>
                            <div class="text-center">
                                <p class="mb-0">Đã có tài khoản?
                                    <a href="{{ route('login') }}" class="text-decoration-none text-primary">Đăng nhập
                                        ngay</a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Script toggle password --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mật khẩu chính
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('togglePasswordIcon');

            togglePassword.addEventListener('click', function() {
                const type = passwordInput.type === 'password' ? 'text' : 'password';
                passwordInput.type = type;
                toggleIcon.classList.toggle('fa-eye');
                toggleIcon.classList.toggle('fa-eye-slash');
            });

            // Mật khẩu xác nhận
            const togglePasswordConfirm = document.getElementById('togglePasswordConfirm');
            const passwordConfirmInput = document.getElementById('password_confirmation');
            const toggleIconConfirm = document.getElementById('togglePasswordConfirmIcon');

            togglePasswordConfirm.addEventListener('click', function() {
                const type = passwordConfirmInput.type === 'password' ? 'text' : 'password';
                passwordConfirmInput.type = type;
                toggleIconConfirm.classList.toggle('fa-eye');
                toggleIconConfirm.classList.toggle('fa-eye-slash');
            });
        });
    </script>

    {{-- CSS riêng --}}
    <style>
        #password,
        #password_confirmation {
            padding-right: 3rem;
            /* chừa chỗ cho icon bên trong */
        }

        .btn.bg-transparent {
            color: #6c757d;
            cursor: pointer;
        }

        .btn.bg-transparent:hover {
            color: #43727F;
        }
    </style>
@endsection
