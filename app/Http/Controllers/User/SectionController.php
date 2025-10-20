<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Section;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    // List all sections (for user-facing pages)
    public function index()
    {
        $sections = Section::withCount('products')->orderBy('name')->get();
        return view('sections.index', compact('sections'));
    }

    // Show products within a section
    public function show(Section $section)
    {
        // eager load product relations needed on the list
        $products = $section->products()->with('images')->where('status', 1)->paginate(12);
        return view('sections.show', compact('section', 'products'));
    }
}
