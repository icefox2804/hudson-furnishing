<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('role');

        // lọc theo tên
        if ($keyword = $request->input('keyword')) {
            $query->where(function($q) use ($keyword){
                $q->where('name', 'like', "%$keyword%")
                ->orWhere('email', 'like', "%$keyword%");
            });
        }

        // lọc theo vai trò
        if ($role = $request->input('role')) {
            $query->whereHas('role', function($q) use ($role){
                $q->where('name', $role);
            });
        }

        $users = $query->paginate(10)->withQueryString();
        return view('admin.users.index', compact('users'));
    }



    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role_id'  => 'required|exists:roles,id',
        ], [
            'name'              => 'Tên là bắt buộc',
            'email.required'    => 'Email là bắt buộc',
            'email.email'       => 'Email không hợp lệ',
            'email.unique'      => 'Email đã tồn tại',
            'password.required' => 'Mật khẩu là bắt buộc',
            'password.min'      => 'Mật khẩu phải có ít nhất 6 ký tự',
            'role_id.required'  => 'Vai trò là bắt buộc',
            'role_id.exists'    => 'Vai trò không hợp lệ',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Thêm người dùng thành công!');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'    => 'required',
            'email'   => 'required|email|unique:users,email,' . $user->id,
            'role_id' => 'required|exists:roles,id',
        ], [
            'name'               => 'Tên là bắt buộc',
            'email.required'     => 'Email là bắt buộc',
            'email.email'        => 'Email không hợp lệ',
            'email.unique'       => 'Email đã tồn tại',
            'role_id.required'   => 'Vai trò là bắt buộc',
            'role_id.exists'     => 'Vai trò không hợp lệ',
        ]);

        $data = $request->only('name', 'email', 'role_id');

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Cập nhật người dùng thành công!');
    }

    public function destroy(User $user)
    {
        if (auth()->id() == $user->id) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'Bạn không thể xóa chính mình!');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Xóa người dùng thành công!');
    }
}
