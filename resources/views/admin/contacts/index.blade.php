@extends('layouts.admin')

@section('title', 'Quản Lý Liên Hệ - Hudson Furnishing')
@section('page-title', 'Quản Lý Liên Hệ')

@section('content')
<div class="card shadow">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-light">Tất Cả Tin Nhắn Liên Hệ</h6>
    </div>
    <div class="card-body">
        <!-- Standalone Filter -->
        <x-standalone-filter 
            :formAction="route('admin.contacts.index')" 
            :filterConfig="[
                'filters' => [
                    ['type' => 'text', 'name' => 'search', 'placeholder' => 'Tìm tên, email...', 'label' => 'Tìm kiếm'],
                    ['type' => 'select', 'name' => 'status', 'placeholder' => 'Tất cả', 'label' => 'Tình trạng', 'options' => ['new' => 'Mới', 'read' => 'Đã đọc', 'replied' => 'Đã trả lời']],
                    ['type' => 'date', 'name' => 'created_from', 'label' => 'Từ ngày'],
                    ['type' => 'date', 'name' => 'created_to', 'label' => 'Đến ngày']
                ]
            ]"
        />

        <!-- Contacts Table -->
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Họ Tên</th>
                        <th>Email</th>
                        <th>Số Điện Thoại</th>
                        <th>Nội Dung</th>
                        <th>Tình Trạng</th>
                        <th>Ngày Tạo</th>
                        <th>Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contacts as $contact)
                        <tr>
                            <td>{{ $contact->name }}</td>
                            <td>{{ $contact->email }}</td>
                            <td>{{ $contact->phone ?? 'N/A' }}</td>
                            <td>{{ Str::limit($contact->message, 100) }}</td>
                            <td>
                                <span class="badge bg-{{ $contact->status == 'new' ? 'warning' : ($contact->status == 'read' ? 'info' : 'success') }}">
                                    {{ ucfirst($contact->status) }}
                                </span>
                            </td>
                            <td>{{ $contact->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.contacts.show', $contact) }}" 
                                       class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($contact->status == 'new')
                                        <form method="POST" action="{{ route('admin.contacts.read', $contact) }}" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @endif
                                    @if($contact->status == 'read')
                                        <form method="POST" action="{{ route('admin.contacts.replied', $contact) }}" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-success">
                                                <i class="fas fa-reply"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <form method="POST" action="{{ route('admin.contacts.destroy', $contact) }}" 
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
                            <td colspan="7" class="text-center py-4">
                                <i class="fas fa-envelope fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Không tìm thấy tin nhắn liên hệ nào</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $contacts->links() }}
        </div>
    </div>
</div>
@endsection
