<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        $query = Brand::withCount('products');

        // Search by name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter by product count
        if ($request->filled('product_count')) {
            switch ($request->product_count) {
                case '0':
                    $query->having('products_count', '=', 0);
                    break;
                case '1-10':
                    $query->having('products_count', '>=', 1)->having('products_count', '<=', 10);
                    break;
                case '11-50':
                    $query->having('products_count', '>=', 11)->having('products_count', '<=', 50);
                    break;
                case '51+':
                    $query->having('products_count', '>=', 51);
                    break;
            }
        }

        // Filter by creation date
        if ($request->filled('created_from')) {
            $query->whereDate('created_at', '>=', $request->created_from);
        }

        $query->orderBy('created_at', 'desc');
        $brands = $query->paginate(15)->withQueryString();

        return view('admin.brands.index', compact('brands'));
    }

    public function create()
    {
        return view('admin.brands.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:brands',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'name.required' => 'Tên thương hiệu không được để trống.',
            'name.unique'   => 'Tên thương hiệu đã tồn tại.',
            'logo.image'    => 'File tải lên phải là hình ảnh.',
            'logo.mimes'    => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif.',
            'logo.max'      => 'Kích thước hình ảnh không được vượt quá 2MB.',
        ]);

        $brand = Brand::create([
        'name' => $request->name,
        'slug' => \Illuminate\Support\Str::slug($request->name),
    ]);


        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = 'brands/' . $brand->id . '-' . time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public', $filename);

            $brand->logo = $filename;
            $brand->save();
        }

        return redirect()->route('admin.brands.index')->with('success', 'Thêm thương hiệu mới thành công!');
    }

    public function show(Brand $brand)
    {
        $products = $brand->products()->with(['section', 'category', 'brand', 'material', 'images'])->paginate(12);
        return view('admin.brands.show', compact('brand', 'products'));
    }

    public function edit(Brand $brand)
    {
        return view('admin.brands.edit', compact('brand'));
    }

    public function update(Request $request, Brand $brand)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:brands,name,' . $brand->id,
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'name.required' => 'Tên thương hiệu không được để trống.',
            'name.unique'   => 'Tên thương hiệu đã tồn tại.',
            'logo.image'    => 'File tải lên phải là hình ảnh.',
            'logo.mimes'    => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif.',
            'logo.max'      => 'Kích thước hình ảnh không được vượt quá 2MB.',
        ]);

        $brand->name = $request->name;

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = 'brands/' . $brand->id . '-' . time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public', $filename);

            // Xóa logo cũ nếu có
            if ($brand->logo && Storage::disk('public')->exists($brand->logo)) {
                Storage::disk('public')->delete($brand->logo);
            }

            $brand->logo = $filename;
        }

        $brand->save();

        return redirect()->route('admin.brands.index')->with('success', 'Cập nhật thương hiệu thành công!');
    }

    public function destroy(Brand $brand)
    {
        if ($brand->products()->count() > 0) {
            return redirect()->route('admin.brands.index')->with('error', 'Không thể xóa thương hiệu có sản phẩm liên quan!');
        }

        $brand->delete();
        return redirect()->route('admin.brands.index')->with('success', 'Thương hiệu đã được xóa thành công!');
    }
}
