<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LiveChatSession;

class ChatController extends Controller
{
    public function index()
    {
        $sessions = LiveChatSession::withCount('messages')->latest()->get();
        return view('admin.chat.index', compact('sessions'));
    }

    // Xem chi tiết session
    public function show($id)
    {
        $session = LiveChatSession::with('messages')->where('id', $id)->firstOrFail();
        return view('admin.chat.show', compact('session'));
    }

    // API lấy lịch sử tin nhắn theo session_id
    public function history($sessionId)
    {
        $messages = LiveChatMessage::where('session_id', $sessionId)
            ->orderBy('created_at')
            ->get(['sender', 'message', 'created_at']);

        return response()->json($messages);
    }
}
