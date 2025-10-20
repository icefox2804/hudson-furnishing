@extends('layouts.admin')

@section('title', 'Quản Lý Khu Vực - Hudson Furnishing')
@section('page-title', 'Quản Lý Khu Vực')

@section('page-actions')
    <a href="{{ route('admin.sections.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Thêm Khu Vực
    </a>
@endsection

@section('content')
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-light"> Tất Cả Khu Vực</h6>
        </div>
        <div class="card-body">

            <!-- Standalone Filter (optional reuse) -->
            
                <x-standalone-filter :formAction="route('admin.sections.index')" :filterConfig="[
                    'filters' => [
                        [
                            'type' => 'text',
                            'name' => 'search',
                            'placeholder' => 'Tìm tên khu vực...',
                            'label' => 'Tìm kiếm',
                        ],
                        ['type' => 'select', 'name' => 'product_count', 'placeholder' => 'Tất cả', 'label' => 'Số sản phẩm', 'options' => ['0' => 'Không có sản phẩm', '1-10' => '1-10 sản phẩm', '11-50' => '11-50 sản phẩm', '51+' => 'Trên 50 sản phẩm']],
                        ['type' => 'date', 'name' => 'created_from', 'label' => 'Từ ngày'],
                    ],
                ]" />
            

            <!-- Sections Table -->
            <div class="table-responsive">
                <table class="table table-bordered admin-table sections-table">
                    <thead>
                        <tr>
                            <th>Tên Khu Vực</th>
                            <th>Số Lượng Sản Phẩm</th>
                            <th>Ngày Tạo</th>
                            <th>Hành Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sections as $section)
                            <tr>
                                <td>{{ $section->name }}</td>
                                <td>{{ $section->products_count ?? $section->products()->count() }}</td>
                                <td>{{ $section->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.sections.show', $section) }}"
                                            class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.sections.edit', $section) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.sections.destroy', $section) }}"
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
                                <td colspan="4" class="text-center py-4">
                                    <i class="fas fa-th-large fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Không tìm thấy khu vực nào</p>
                                    <a href="{{ route('admin.sections.create') }}" class="btn btn-primary">
                                        Thêm khu vực đầu tiên của bạn
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $sections->links() }}
            </div>
        </div>
    </div>
@endsection
