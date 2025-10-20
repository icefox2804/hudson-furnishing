<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Trang dashboard
    public function dashboard()
    {
        $user = Auth::user(); // người dùng hiện tại

        // Lấy danh sách sản phẩm yêu thích kèm thông tin sản phẩm
        $favorites = $user->favorites()->with('product.images')->get();

        //$user->favorites() là quan hệ Favorite từ User đến bảng favorites.
        //with('product') load quan hệ Product.
        //pluck('product') lấy trực tiếp các Product để tiện hiển thị.

        return view('user.dashboard', compact('user', 'favorites'));
    }

    // Trang profile
    public function profile()
    {
        $user = Auth::user();
        return view('user.profile', compact('user'));
    }

    // Cập nhật thông tin cá nhân
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
        ]);

        $user = Auth::user();
        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        return back()->with('success', 'Cập nhật thông tin thành công!');
    }

    // Cập nhật mật khẩu
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:6|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Đổi mật khẩu thành công!');
    }

    // Danh sách sản phẩm yêu thích
    public function favorites()
    {
        $user = Auth::user();
        $favorites = $user->favorites()->with('product')->get();

        return view('user.favorites', compact('favorites'));
    }
}
