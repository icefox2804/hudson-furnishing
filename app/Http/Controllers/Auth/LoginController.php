<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle a login request.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string|min:8',
        ], [
            'email.required'    => 'Vui lòng nhập email của bạn.',
            'email.email'       => 'Địa chỉ email không hợp lệ.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min'      => 'Mật khẩu phải có ít nhất 8 ký tự.',
        ]);

        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Redirect based on user role
            if (auth()->user()->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'));
            } else {
                return redirect()->intended(route('home'));
            }
        }

        throw ValidationException::withMessages([
            'email' => 'Email hoặc mật khẩu không đúng. Vui lòng thử lại!',
        ]);
    }

    /**
     * Handle a logout request.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
