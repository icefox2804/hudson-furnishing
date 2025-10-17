@extends('layouts.admin')

@section('title', 'Create Category - Hudson Furnishing')
@section('page-title', 'Tạo Danh Mục')

@section('content')
<div class="card shadow">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-light">Tạo Danh Mục Mới</h6>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.categories.store') }}">
            @csrf
            
            <div class="mb-3">
                <label for="name" class="form-label">Tên Danh Mục *</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                       id="name" name="name" value="{{ old('name') }}">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="section_id" class="form-label">Khu Vực</label>
                <select class="form-select @error('section_id') is-invalid @enderror" 
                        id="section_id" name="section_id">
                    <option value="">Chọn Khu Vực</option>
                    @foreach($sections as $section)
                        <option value="{{ $section->id }}" {{ old('section_id') == $section->id ? 'selected' : '' }}>
                            {{ $section->name }}
                        </option>
                    @endforeach
                </select>
                @error('section_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="text-end">
                <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Tạo Danh Mục</button>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary me-2">Hủy</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
