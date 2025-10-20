@extends('layouts.guest')

@section('title', 'Thư Viện Tham Khảo - Hudson Furnishing')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold">Thư Viện Tham Khảo</h1>
        <p class="lead text-muted">Khám phá các bộ sưu tập nội thất mới nhất!</p>
    </div>
    
    <div class="row">
        @forelse($images as $image)
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="card h-100">
                    <img src="{{ asset('storage/uploads/' . $image->url) }}" 
                         class="card-img-top" 
                         alt="{{ $image->alt_text }}"
                         style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <h6 class="card-title">{{ $image->product->name }}</h6>
                        <p class="card-text text-muted">{{ $image->product->section->name }}</p>
                        <a href="{{ route('product.show', $image->product->slug) }}" class="btn btn-sm btn-outline-primary">Xem Sản Phẩm</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="fas fa-images fa-3x text-muted mb-3"></i>
                    <h4>Không có hình ảnh nào</h4>
                    <p class="text-muted">Quay lại để xem các bộ sưu tập mới nhất!</p>
                </div>
            </div>
        @endforelse
    </div>
    
    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $images->links() }}
    </div>
</div>
@endsection
