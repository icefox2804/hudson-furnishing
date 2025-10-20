@extends('layouts.admin')

@section('title', 'Tạo Khu Vực - Hudson Furnishing')
@section('page-title', 'Tạo Khu Vực')

@section('content')
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-light">Tạo Khu Vực Mới</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.sections.store') }}">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Tên Khu Vực *</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                        name="name" value="{{ old('name') }}" >
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="slug" class="form-label">Slug (tùy chọn)</label>
                    <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug"
                        name="slug" value="{{ old('slug') }}">
                    @error('slug')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Mô Tả</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                        rows="4">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="text-end">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Tạo Khu Vực</button>
                        <a href="{{ route('admin.sections.index') }}" class="btn btn-secondary me-2">Hủy</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
