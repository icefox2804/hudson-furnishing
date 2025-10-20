@extends('layouts.admin')

@section('title', 'Chat Admin')

@section('scripts')
    @vite(['resources/js/commonchat.js'])
@endsection

@section('content')
<div class="container mt-4">
    <h3>Chat với {{ $session->username ?? 'Khách' }} (Session: {{ $session->session_id }})</h3>

    {{-- Vùng hiển thị tin nhắn --}}
    <div id="chat-messages" 
         data-session="{{ $session->session_id }}"
         style="border:1px solid #ccc; padding:10px; height:400px; overflow-y:auto;">
        @foreach ($session->messages as $msg)
            <div class="chat-message {{ $msg->sender }}">
                <strong>{{ ucfirst($msg->sender) }}:</strong> {{ $msg->message }}
            </div>
        @endforeach
    </div>

    {{-- Form gửi tin nhắn --}}
    <form id="chat-input-form" class="mt-3 d-flex">
        <input type="hidden" name="session_id" value="{{ $session->session_id }}">
        <input type="text" id="chat-input" name="message" class="form-control me-2" placeholder="Nhập tin nhắn...">
        <button type="submit" class="btn btn-primary">Gửi</button>
    </form>
</div>
@endsection

@push('styles')
<style>
.chat-message {
    padding: 8px 12px;
    border-radius: 12px;
    max-width: 80%;
    margin-bottom: 6px;
    word-wrap: break-word;
}
.chat-message.user {
    background-color: #007bff;
    color: white;
    align-self: flex-start;
}
.chat-message.admin {
    background-color: #ffe599;
    color: #333;
    align-self: flex-end;
}
</style>
@endpush
