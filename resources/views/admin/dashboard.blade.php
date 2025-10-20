@extends('layouts.admin')

@section('title', 'Bảng Điều Khiển - Hudson Furnishing')
@section('page-title', 'Bảng Điều Khiển')

@section('content')
<!-- Statistics Cards -->
<div class="row mb-4">
    {{-- Tổng sản phẩm --}}
    @if(auth()->user()->isAdmin() || auth()->user()->isStaff())
        <div class="col-xl-3 col-md-6 mb-4">
            <a href="{{ route('admin.products.index') }}" style="text-decoration: none;">
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
            </a>
        </div>
    @endif

    {{-- Sản phẩm đang bán --}}
    @if(auth()->user()->isAdmin() || auth()->user()->isStaff())
        <div class="col-xl-3 col-md-6 mb-4">
            <a href="{{ route('admin.products.index', ['status' => 'active']) }}" style="text-decoration: none;">
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
            </a>
        </div>
    @endif

    {{-- Tổng lượt truy cập (chỉ admin) --}}
    @if(auth()->user()->isAdmin())
        <div class="col-xl-3 col-md-6 mb-4">
            <a href="{{ route('admin.visitors.index') }}" style="text-decoration: none;">
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
            </a>
        </div>
    @endif

    {{-- Đánh giá chờ duyệt (admin + staff) --}}
    @if(auth()->user()->isAdmin() || auth()->user()->isStaff())
        <div class="col-xl-3 col-md-6 mb-4">
            <a href="{{ route('admin.reviews.index', ['approved' => 0]) }}" style="text-decoration: none;">
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
            </a>
        </div>
    @endif

    {{-- Tổng người dùng (chỉ admin) --}}
    @if(auth()->user()->isAdmin())
        <div class="col-xl-3 col-md-6 mb-4">
            <a href="{{ route('admin.users.index') }}" style="text-decoration: none;">
                <div class="card border-left-dark shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">Tổng Người Dùng</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_users'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    @endif
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
                    @if(auth()->user()->isAdmin() || auth()->user()->isStaff())
                        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Thêm Sản Phẩm Mới
                        </a>
                        <a href="{{ route('admin.reviews.index') }}" class="btn btn-warning">
                            <i class="fas fa-star me-2"></i>Xem Đánh Giá Chờ Duyệt
                        </a>
                    @endif

                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.gallery.index') }}" class="btn btn-info">
                            <i class="fas fa-images me-2"></i>Quản Lý Gallery
                        </a>
                        <a href="{{ route('admin.settings.index') }}" class="btn btn-secondary">
                            <i class="fas fa-cog me-2"></i>Cài Đặt Website
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-primary">
                            <i class="fas fa-user me-2"></i>Quản Lý User
                        </a>
                        <a href="{{ route('admin.chat.index') }}" class="btn btn-secondary">
                            <i class="fa-regular fa-comments"></i> Quản Lý Chat
                        </a>
                    @endif
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
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0;">
                        <thead>
                            <tr>
                                <th style="border: 1px solid #e3e6f0; padding: 0.75rem 0.5rem; background-color: #f8f9fa; font-weight: 600; white-space: nowrap; text-align: left;">Tên Sản Phẩm</th>
                                <th style="border: 1px solid #e3e6f0; padding: 0.75rem 0.5rem; background-color: #f8f9fa; font-weight: 600; white-space: nowrap; text-align: left;">Mục Sản Phẩm</th>
                                <th style="border: 1px solid #e3e6f0; padding: 0.75rem 0.5rem; background-color: #f8f9fa; font-weight: 600; white-space: nowrap; text-align: left;">Tình Trạng</th>
                                <th style="border: 1px solid #e3e6f0; padding: 0.75rem 0.5rem; background-color: #f8f9fa; font-weight: 600; white-space: nowrap; text-align: left;">Tạo Lúc</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentProducts as $product)
                                <tr>
                                    <td style="border: 1px solid #e3e6f0; padding: 0.75rem 0.5rem; max-width: 200px; word-wrap: break-word; line-height: 1.2; vertical-align: middle;">
                                        <div style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.2; max-height: 2.4em;">
                                            {{ $product->name }}
                                        </div>
                                    </td>
                                    <td style="border: 1px solid #e3e6f0; padding: 0.75rem 0.5rem; white-space: nowrap; vertical-align: middle;">{{ $product->section->name }}</td>
                                    <td style="border: 1px solid #e3e6f0; padding: 0.75rem 0.5rem; white-space: nowrap; vertical-align: middle;">                                        
                                        @php
                                            $options = \App\Helpers\StatusHelper::getStatusOptions();
                                            $status = $product->status;
                                        @endphp
                                        <span class="badge bg-{{ $options[$status]['class'] ?? 'warning' }}">
                                            {{ $options[$status]['label'] ?? ucfirst($status) }}
                                        </span>
                                    </td>                                    
                                    <td style="border: 1px solid #e3e6f0; padding: 0.75rem 0.5rem; white-space: nowrap; font-size: 0.875rem; color: #6c757d; vertical-align: middle;">{{ $product->created_at->format('d/m/Y') }}</td>
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
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0;">
                        <thead>
                            <tr>
                                <th style="border: 1px solid #e3e6f0; padding: 0.75rem 0.5rem; background-color: #f8f9fa; font-weight: 600; white-space: nowrap; text-align: left;">Sản Phẩm</th>
                                <th style="border: 1px solid #e3e6f0; padding: 0.75rem 0.5rem; background-color: #f8f9fa; font-weight: 600; white-space: nowrap; text-align: left;">Xếp Hạng</th>
                                <th style="border: 1px solid #e3e6f0; padding: 0.75rem 0.5rem; background-color: #f8f9fa; font-weight: 600; white-space: nowrap; text-align: left;">Tình Trạng</th>
                                <th style="border: 1px solid #e3e6f0; padding: 0.75rem 0.5rem; background-color: #f8f9fa; font-weight: 600; white-space: nowrap; text-align: left;">Ngày Tạo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentReviews as $review)
                                <tr>
                                    <td style="border: 1px solid #e3e6f0; padding: 0.75rem 0.5rem; max-width: 200px; word-wrap: break-word; line-height: 1.2; vertical-align: middle;">
                                        <div style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.2; max-height: 2.4em;">
                                            {{ $review->product->name }}
                                        </div>
                                    </td>
                                    <td style="border: 1px solid #e3e6f0; padding: 0.75rem 0.5rem; white-space: nowrap; vertical-align: middle;">
                                        <div class="text-warning" style="display: flex; align-items: center; gap: 1px; white-space: nowrap;">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star{{ $i <= $review->rating ? '' : '-o' }}" style="font-size: 0.875rem;"></i>
                                            @endfor
                                        </div>
                                    </td>
                                    <td style="border: 1px solid #e3e6f0; padding: 0.75rem 0.5rem; white-space: nowrap; vertical-align: middle;">
                                        @php
                                            $options = \App\Helpers\StatusHelper::getReviewStatusOption();
                                            $status = $review->approved;
                                        @endphp
                                        <span class="badge bg-{{ $options[$status]['class'] ?? 'warning' }}">
                                            {{ $options[$status]['label'] ?? ucfirst($status) }}
                                        </span>
                                    </td>
                                    <td style="border: 1px solid #e3e6f0; padding: 0.75rem 0.5rem; white-space: nowrap; font-size: 0.875rem; color: #6c757d; vertical-align: middle;">{{ $review->created_at->format('d/m/Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
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
            label: 'Tổng lượt truy cập',
            data: [
                @foreach($visitorStats as $stat)
                    {{ $stat->total_visits }},
                @endforeach
            ],
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.1
        }, {
            label: 'số lượt truy cập',
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
