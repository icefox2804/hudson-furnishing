<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use App\Models\User;

class ResetPasswordController extends Controller
{
    public function showResetForm($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    public function reset(Request $request){
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email|exists:users,email',
            'password' => 'required|min:8|confirmed',
        ], [
            'email.required'     => 'Vui lòng nhập email của bạn.',
            'email.email'        => 'Địa chỉ email không hợp lệ.',
            'email.exists'       => 'Email không tồn tại trong hệ thống.',
            'password.required'  => 'Vui lòng nhập mật khẩu mới.',
            'password.min'       => 'Mật khẩu mới phải có ít nhất 8 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu mới không khớp.',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, $password) {
                $user->password = Hash::make($password);
                $user->save();
                
            }
        );

        $message = match ($status) {
            Password::PASSWORD_RESET  => 'Mật khẩu của bạn đã được đặt lại thành công!',
            Password::RESET_LINK_SENT => 'Chúng tôi đã gửi email khôi phục mật khẩu cho bạn!',
            Password::INVALID_TOKEN   => 'Mã đặt lại mật khẩu không hợp lệ.',
            Password::INVALID_USER    => 'Không tìm thấy người dùng với email này.',
            Password::RESET_THROTTLED => 'Vui lòng chờ trước khi thử lại.', 
            default => 'Vui lòng chờ trước khi thử lại.',
        };

        return $status === Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('status', $message)
                    : back()->withErrors(['email' => $message]);
    }
}
