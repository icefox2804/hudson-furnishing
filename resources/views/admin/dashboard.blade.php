@extends('layouts.admin')

@section('title', 'Bảng Điều Khiển - Hudson Furnishing')
@section('page-title', 'Bảng Điều Khiển')

@section('content')
<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Tổng Sản Phẩm</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_products'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-box fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Sản Phẩm Đang Bán</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['active_products'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Tổng Lượt Truy Cập</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['total_visitors']) }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Đánh Giá Chờ Duyệt</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending_reviews'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-star fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row">
    <!-- Visitor Chart -->
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-light">Thống Kê Khách Truy Cập (7 Ngày Gần Nhất)</h6>
            </div>
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="visitorChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-light">Thao Tác Nhanh</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Thêm Sản Phẩm Mới
                    </a>
                    <a href="{{ route('admin.reviews.index') }}" class="btn btn-warning">
                        <i class="fas fa-star me-2"></i>Xem Đánh Giá Chờ Duyệt
                    </a>
                    <a href="{{ route('admin.gallery.index') }}" class="btn btn-info">
                        <i class="fas fa-images me-2"></i>Quản Lý Gallery
                    </a>
                    <a href="{{ route('admin.settings.index') }}" class="btn btn-secondary">
                        <i class="fas fa-cog me-2"></i>Cài Đặt Website
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activities -->
<div class="row">
    <div class="col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-light">Sản Phẩm Mới Nhất</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Tên Sản Phẩm</th>
                                <th>Mục Sản Phẩm</th>
                                <th>Tình Trạng</th>
                                <th>Tạo Lúc</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentProducts as $product)
                                <tr>
                                    <td>{{ Str::limit($product->name, 20) }}</td>
                                    <td>{{ $product->section->name }}</td>
                                    <td>
                                        <span class="badge bg-{{ $product->status == 'active' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($product->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $product->created_at->format('M d') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-light">Đánh Giá Mới Nhất</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Sản Phẩm</th>
                                <th>Xếp Hạng</th>
                                <th>Tình Trạng</th>
                                <th>Ngày Tạo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentReviews as $review)
                                <tr>
                                    <td>{{ Str::limit($review->product->name, 20) }}</td>
                                    <td>
                                        <div class="text-warning">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star{{ $i <= $review->rating ? '' : '-o' }}"></i>
                                            @endfor
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $review->approved ? 'success' : 'warning' }}">
                                            {{ $review->approved ? 'Approved' : 'Pending' }}
                                        </span>
                                    </td>
                                    <td>{{ $review->created_at->format('M d') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Visitor Chart
const ctx = document.getElementById('visitorChart').getContext('2d');
const visitorChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: [
            @foreach($visitorStats as $stat)
                '{{ $stat->date->format("M d") }}',
            @endforeach
        ],
        datasets: [{
            label: 'Total Visits',
            data: [
                @foreach($visitorStats as $stat)
                    {{ $stat->total_visits }},
                @endforeach
            ],
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.1
        }, {
            label: 'Unique Visits',
            data: [
                @foreach($visitorStats as $stat)
                    {{ $stat->unique_visits }},
                @endforeach
            ],
            borderColor: 'rgb(255, 99, 132)',
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>
@endpush
