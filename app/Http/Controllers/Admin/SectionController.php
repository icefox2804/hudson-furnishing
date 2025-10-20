<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;

class SectionController extends Controller
{
    public function index(Request $request)
    {
        // eager load product counts for performance
        $sections = Section::withCount('products')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.sections.index', compact('sections'));
    }

    public function create()
    {
        return view('admin.sections.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:sections,name',
            'slug' => 'nullable|string|max:255|unique:sections,slug',
            'description' => 'nullable|string',
        ],[
            'name.required' => 'Vui nhập tên khu vực',
        ]);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
            $original = $data['slug'];
            $i = 1;
            while (Section::where('slug', $data['slug'])->exists()) {
                $data['slug'] = $original . '-' . $i++;
            }
        }

        Section::create($data);

        return Redirect::route('admin.sections.index')->with('success', 'Khu vực đã được tạo.');
    }

    public function edit(Section $section)
    {
        return view('admin.sections.edit', compact('section'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Section $section)
    {
        // Load products paginated for the section detail view
        $products = $section->products()->with(['brand', 'category'])->paginate(12);

        return view('admin.sections.show', compact('section', 'products'));
    }

    public function update(Request $request, Section $section)
    {
        $data = $request->validate([
            'name'  => 'required|string|max:255|unique:sections,name,' . $section->id,
            'slug'  => 'nullable|string|max:255|unique:sections,slug,' . $section->id,
            'description' => 'nullable|string',
        ],[
            'name.required' => 'Vui nhập tên khu vực',
        ]);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
            $original = $data['slug'];
            $i = 1;
            while (Section::where('slug', $data['slug'])->where('id', '!=', $section->id)->exists()) {
                $data['slug'] = $original . '-' . $i++;
            }
        }

        $section->update($data);

        return Redirect::route('admin.sections.index')->with('success', 'Khu vực đã được cập nhật.');
    }

    public function destroy(Section $section)
    {
        $section->delete();
        return Redirect::route('admin.sections.index')->with('success', 'Khu vực đã được xóa.');
    }
}

