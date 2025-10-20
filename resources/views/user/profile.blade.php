@extends('layouts.app')

@section('title', 'Cập nhật thông tin cá nhân')

@section('content')
    <div class="container mt-4">
        <h3>🧾 Cập nhật thông tin cá nhân</h3>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('user.profile.update') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label>Tên</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control">
                @error('name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control">
                @error('email')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
        </form>

        <hr>

        <h4>🔒 Đổi mật khẩu</h4>
        <form action="{{ route('user.password.update') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label>Mật khẩu hiện tại</label>
                <input type="password" name="current_password" class="form-control">
            </div>
            <div class="mb-3">
                <label>Mật khẩu mới</label>
                <input type="password" name="password" class="form-control">
            </div>
            <div class="mb-3">
                <label>Nhập lại mật khẩu mới</label>
                <input type="password" name="password_confirmation" class="form-control">
            </div>
            <button type="submit" class="btn btn-warning">Đổi mật khẩu</button>
        </form>
    </div>
@endsection
