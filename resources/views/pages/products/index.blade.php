@extends('layouts.guest')

@section('title', 'Sản Phẩm - Hudson Furnishing')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Sidebar Filters -->
        <div class="col-lg-3">
            <x-standalone-filter :formAction="route('products.index')" :columns="1" :filterConfig="[
                    'filters' => [
                        [
                            'type' => 'text',
                            'name' => 'search',
                            'placeholder' => 'Tìm tên sản phẩm...',
                            'label' => 'Tìm kiếm',
                        ],
                        [
                            'type' => 'select',
                            'name' => 'section',
                            'placeholder' => 'Tất cả không gian',
                            'label' => 'Không gian',
                            'options' => $sections->pluck('name', 'slug')->toArray(),
                        ],
                        [
                            'type' => 'select',
                            'name' => 'material',
                            'placeholder' => 'Tất cả chất liệu',
                            'label' => 'Chất liệu',
                            'options' => $materials->pluck('name', 'id')->toArray(),
                            ],
                        [
                            'type' => 'select',
                            'name' => 'category',
                            'placeholder' => 'Tất cả danh mục',
                            'label' => 'Danh mục',
                            'options' => $categories->pluck('name', 'id')->toArray(),
                        ],
                        [
                            'type' => 'select',
                            'name' => 'brand',
                            'placeholder' => 'Tất cả thương hiệu',
                            'label' => 'Thương hiệu',
                            'options' => $brands->pluck('name', 'id')->toArray(),
                        ],
                        ['type' => 'price_range', 'name' => 'price_range', 'label' => 'Khoảng giá'],
                        ['type' => 'stock_range', 'name' => 'stock_range', 'label' => 'Số lượng sản phẩm'],
                        
                    ],
                ]" />
        </div>

        <!-- product list -->
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Sản Phẩm ({{ $products->total() }} sản phẩm)</h2>
                <div class="d-flex gap-2">
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            Sắp Xếp sản phẩm theo
                        </button>
                        <ul class="dropdown-menu">
                            {{-- Tên --}}
                            <li>
                                <a class="dropdown-item {{ request('sort_by') === 'name' ? 'active' : '' }}"
                                href="{{ request()->fullUrlWithQuery([
                                    'sort_by' => 'name',
                                    'sort_order' => (request('sort_by') === 'name' && request('sort_order') === 'asc') ? 'desc' : 'asc',
                                    'page' => 1
                                ]) }}">
                                    Tên
                                    @if(request('sort_by') === 'name')
                                        <span class="float-end">{{ request('sort_order') === 'asc' ? '↑' : '↓' }}</span>
                                    @endif
                                </a>
                            </li>

                            {{-- Giá --}}
                            <li>
                                <a class="dropdown-item {{ request('sort_by') === 'price' ? 'active' : '' }}"
                                href="{{ request()->fullUrlWithQuery([
                                    'sort_by' => 'price',
                                    'sort_order' => (request('sort_by') === 'price' && request('sort_order') === 'asc') ? 'desc' : 'asc',
                                    'page' => 1
                                ]) }}">
                                    Giá
                                    @if(request('sort_by') === 'price')
                                        <span class="float-end">{{ request('sort_order') === 'asc' ? '↑' : '↓' }}</span>
                                    @endif
                                </a>
                            </li>

                            {{-- Ngày --}}
                            <li>
                                <a class="dropdown-item {{ request('sort_by') === 'created_at' ? 'active' : '' }}"
                                href="{{ request()->fullUrlWithQuery([
                                    'sort_by' => 'created_at',
                                    'sort_order' => (request('sort_by') === 'created_at' && request('sort_order') === 'asc') ? 'desc' : 'asc',
                                    'page' => 1
                                ]) }}">
                                    Ngày
                                    @if(request('sort_by') === 'created_at')
                                        <span class="float-end">{{ request('sort_order') === 'asc' ? '↑' : '↓' }}</span>
                                    @endif
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                @forelse($products as $product)
                    <div class="col-lg-4 col-md-6 mb-4">
                        @include('components.product-card', ['product' => $product])
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center py-5">
                            <h4>Không tìm thấy sản phẩm</h4>
                            <p>Kiểm tra lại bộ lọc của bạn</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                <nav aria-label="Sản Phẩm Phân Trang">
                    {{ $products->appends(request()->query())->links() }}
                </nav>
            </div>
        </div>
    </div>
</div>
@endsection
