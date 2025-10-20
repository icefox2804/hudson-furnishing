<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm() {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request) {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'Vui lòng nhập email của bạn.',
            'email.email'    => 'Địa chỉ email không hợp lệ.',
            'email.exists'   => 'Email không tồn tại trong hệ thống.',
        ]);

        $user = User::where('email', $request->email)->first();

        $token = Password::createToken($user);
        
        $resetUrl = route('password.reset', [
            'token' => $token, 
            'email' => $user->email
        ]);

        Mail::send('emails.reset-password', ['user' => $user, 'resetUrl' => $resetUrl], function($message) use ($user) {
            $message->to($user->email);
            $message->subject('Đặt lại mật khẩu - Hudson Furnishing');
        });

        return back()->with('status', 'Chúng tôi đã gửi email khôi phục mật khẩu cho bạn!');
    }
}
