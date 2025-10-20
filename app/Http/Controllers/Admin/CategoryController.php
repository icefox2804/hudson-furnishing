<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Section;
use App\Models\Product;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function index(Request $request)
    {
        $query = Category::with('section')->withCount('products');

        // Search by category name
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter by section
        if ($request->has('section') && $request->section != '') {
            $query->where('section_id', $request->section);
        }

        // Filter by product count
        if ($request->has('product_count') && $request->product_count != '') {
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
        if ($request->has('created_from') && $request->created_from != '') {
            $query->whereDate('created_at', '>=', $request->created_from);
        }

        $query->orderBy('created_at', 'desc');
        $categories = $query->paginate(15)->withQueryString();

        $sections = Section::all();

        return view('admin.categories.index', compact('categories', 'sections'));
    }

    public function show(Category $category){
        $category->load('section');

        $products = $category->products()->with(['section', 'category', 'brand', 'material', 'images'])->paginate(12);

        return view('admin.categories.show', compact('category', 'products'));
    }
    
    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        $sections = Section::all();

        return view('admin.categories.create', compact('sections'));
    }

    /**
     * Store a newly created category.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'section_id' => 'required|exists:sections,id',
        ],[
            'name.required'       => 'Vui lòng nhập tên Danh Mục',
            'name.string'         => 'kiêu dữ liệu không hợp lệ',
            'section_id.required' => 'vui lòng chọn Danh Mục',
            'section_id.exists'   => 'Danh Mục khong ton tai',          
        ]);

        Category::create([
            'name' => $request->name,
            'section_id' => $request->section_id,
            'slug' => \Str::slug($request->name),
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Đã Tạo Danh Mục Thành Công.');
    }

    /**
     * Show the form for editing the category.
     */
    public function edit(Category $category)
    {
        $sections = Section::all();

        return view('admin.categories.edit', compact('category', 'sections'));
    }

    /**
     * Update the specified category.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'section_id' => 'required|exists:sections,id',
        ]);

        $category->update([
            'name' => $request->name,
            'section_id' => $request->section_id,
            'slug' => \Str::slug($request->name),
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Đã cập nhật Danh Mục Thành Công.');
    }

    /**
     * Remove the specified category.
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Đã xóa Danh Mục.');
    }
}
