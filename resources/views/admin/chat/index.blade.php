@extends('layouts.admin')

@section('title', 'Danh sách cuộc trò chuyện')

@section('scripts')
    @vite(['resources/js/commonchat.js'])
@endsection

@section('content')
<div class="container mt-4">
    <h3>Danh sách các cuộc trò chuyện</h3>

    @foreach ($sessions as $session)
        <div class="card mb-3 shadow">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <div>
                    <strong>{{ $session->username ?? 'Khách' }}</strong>
                    <small class="text-light">(Session: {{ $session->session_id }})</small>
                </div>
                <a href="{{ route('admin.chat.show', $session->id) }}" class="btn btn-sm btn-light">Mở chat</a>
            </div>
            <div class="card-body" style="max-height: 150px; overflow-y: auto;">
                @foreach ($session->messages->take(-5) as $msg)
                    <div class="mb-2 {{ $msg->sender === 'admin' ? 'text-end' : '' }}">
                        <strong>{{ ucfirst($msg->sender) }}:</strong> {{ $msg->message }}
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>

<script>

</script>
@endsection
