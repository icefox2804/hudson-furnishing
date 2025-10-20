<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * Show the registration form.
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle the registration request.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([

            'name'     => ['required', 'string', 'max:255', new \App\Rules\NotAllNumbers(), 'min:3', new \App\Rules\TrimmedString()],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email', new \App\Rules\TrimmedString()],
            'password' => ['required', 'string', 'confirmed', new \App\Rules\StrongPassword()],
        ], [
            'name.required'      => 'Vui lòng nhập họ và tên.',
            'name.max'           => 'Họ và tên không được quá 255 ký tự.',                 
            'name.string'        => 'Tên phải là chuỗi ký tự.',
            'name.min'           => 'Tên phải có ít nhất 3 ký tự.',
            'email.required'     => 'Vui lòng nhập email của bạn.',
            'email.email'        => 'Địa chỉ email không hợp lệ.',
            'email.unique'       => 'Email đã được sử dụng. Vui lòng chọn email khác.',
            'password.required'  => 'Vui lòng nhập mật khẩu.',
            'password.min'       => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'user',
        ]);

        // Do not auto-login. Force user to sign in after registration.
        return redirect()->route('login')->with('status', 'Đăng ký thành công! Vui lòng đăng nhập.');
    }
}


