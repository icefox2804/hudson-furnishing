@extends('layouts.admin')

@section('title', 'Chi Tiết Khu Vực: ' . $section->name)

@section('content')

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-light">Chi Tiết Khu Vực: {{ $section->name }}</h6>
            <div>
                <a href="{{ route('admin.sections.edit', $section) }}" class="btn btn-secondary btn-sm text-light">
                    <i class="fas fa-edit"></i> Chỉnh Sửa
                </a>
                <a href="{{ route('admin.sections.index') }}" class="btn btn-secondary btn-sm text-light">
                    <i class="fas fa-arrow-left"></i> Quay Lại Danh Sách
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <p><strong>ID Khu Vực:</strong> {{ $section->id }}</p>
                    <p><strong>Tên Khu Vực:</strong> {{ $section->name }}</p>
                    <p><strong>Slug:</strong> {{ $section->slug }}</p>
                    <p><strong>Mô Tả:</strong> {{ $section->description ?? 'Không có mô tả' }}</p>
                    <p><strong>Ngày Tạo:</strong> {{ $section->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>

    ---

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-light">Sản Phẩm Thuộc Khu Vực Này ({{ $products->total() ?? 0 }})</h6>
        </div>
        <div class="card-body">
            @if ($products->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tên Sản Phẩm</th>
                                <th>Danh Mục</th>
                                <th>Thương Hiệu</th>
                                <th>Giá</th>
                                <th>Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr>
                                    <td>{{ $product->id }}</td>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->category->name ?? 'N/A' }}</td>
                                    <td>{{ $product->brand->name ?? 'N/A' }}</td>
                                    <td>{{ number_format($product->price) }} VNĐ</td>
                                    <td>
                                        <a href="{{ route('admin.products.show', $product) }}"
                                            class="btn btn-sm btn-secondary text-light">Xem</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center">
                    {{ $products->links() }}
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Không có sản phẩm nào thuộc khu vực này.
                </div>
            @endif

        </div>
    </div>

@endsection
