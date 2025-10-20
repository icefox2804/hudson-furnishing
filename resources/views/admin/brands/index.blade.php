@extends('layouts.admin')

@section('title', 'Brands Management - Hudson Furnishing')
@section('page-title', 'Brands Management')

@section('page-actions')
    <a href="{{ route('admin.brands.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Thêm Thương Hiệu
    </a>
@endsection

@section('content')
<div class="card shadow">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-light">Tất Cả Thương Hiệu</h6>
    </div>
    <div class="card-body">
        <!-- Standalone Filter -->
        <x-standalone-filter 
            :formAction="route('admin.brands.index')" 
            :filterConfig="[
                'filters' => [
                    ['type' => 'text', 'name' => 'search', 'placeholder' => 'Tìm tên thương hiệu...', 'label' => 'Tìm kiếm'],
                    ['type' => 'select', 'name' => 'product_count', 'placeholder' => 'Tất cả', 'label' => 'Số sản phẩm', 'options' => ['0' => 'Không có sản phẩm', '1-10' => '1-10 sản phẩm', '11-50' => '11-50 sản phẩm', '51+' => 'Trên 50 sản phẩm']],
                    ['type' => 'date', 'name' => 'created_from', 'label' => 'Từ ngày']
                ]
            ]"
        />

        <!-- Brands Table -->
        <div class="table-responsive">
            <table class="table table-bordered admin-table brands-table">
                <thead>
                    <tr>
                        <th>Logo</th>
                        <th>Tên Thương Hiệu</th>
                        <th>Số Lượng Sản Phẩm</th>
                        <th>Ngày Tạo</th>
                        <th>Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($brands as $brand)
                        <tr>
                            <td>
                                @if($brand->logo)
                                    <img src="{{ asset('storage/' . $brand->logo) }}" 
                                         alt="{{ $brand->name }}" class="img-thumbnail admin-table-image">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center admin-table-image">
                                        <i class="fas fa-image text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td>{{ $brand->name }}</td>
                            <td>{{ $brand->products_count }}</td>
                            <td>{{ $brand->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.brands.show', $brand) }}" 
                                       class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.brands.edit', $brand) }}" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.brands.destroy', $brand) }}" 
                                          class="d-inline form-confirm">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <i class="fas fa-star fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Không tìm thấy thương hiệu nào</p>
                                <a href="{{ route('admin.brands.create') }}" class="btn btn-primary">
                                    Thêm Thương Hiệu Đầu Tiên
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $brands->links() }}
        </div>
    </div>
</div>
@endsection
