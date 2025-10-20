<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Section;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Material;

class ProductController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        // Remove auth middleware for public product routes
        $this->middleware('auth')->except(['index', 'bySection', 'show']);
    }

    /**
     * Display a listing of products.
     */
    public function index(Request $request)
    {
        $query = Product::with(['section', 'category', 'brand', 'material', 'images', 'reviews']);

        // Filter by section
        if ($request->has('section') && $request->section) {
            $query->whereHas('section', function($q) use ($request) {
                $q->where('slug', $request->section);
            });
        }

        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        // Filter by brand
        if ($request->has('brand') && $request->brand) {
            $query->where('brand_id', $request->brand);
        }

        // Filter by material
        if ($request->has('material') && $request->material) {
            $query->where('material_id', $request->material);
        }

        // Filter by price range
        if ($request->has('price_range') && $request->price_range) {
            $priceRange = explode('-', $request->price_range);
            if (count($priceRange) == 2) {
                $query->whereBetween('price', [$priceRange[0], $priceRange[1]]);
            }
        }

        // Filter by stock range  
        if ($request->has('stock_range') && $request->stock_range) {
            $stockRange = explode('-', $request->stock_range);
            if (count($stockRange) == 2) {
                $query->whereBetween('stock', [$stockRange[0], $stockRange[1]]);
            }
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        

        // Search by name
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // sắp xếp sản phẩm
        $sortBy = $request->get('sort_by', 'name');
        $sortOrder = $request->get('sort_order', 'asc');
        
        switch ($sortBy) {
            case 'price':
                $query->orderBy('price', $sortOrder);
                break;
            case 'name':
                $query->orderBy('name', $sortOrder);
                break;
            case 'created_at':
                $query->orderBy('created_at', $sortOrder);
                break;
            default:
                $query->orderBy('name', 'asc');
        }

        $products = $query->where('status', 'active')->paginate(12);

        // Get filter options
        $sections = Section::all();
        $categories = Category::all();
        $brands = Brand::all();
        $materials = Material::all();

        return view('pages.products.index', compact('products', 'sections', 'categories', 'brands', 'materials'));
    }

    /**
     * Display products by section.
     */
    public function bySection($sectionSlug, Request $request)
{
    try {
        $section = Section::where('slug', $sectionSlug)->firstOrFail();
    } catch (\Exception $e) {
        abort(404, 'Section not found: ' . $sectionSlug);
    }

    $query = Product::with(['section', 'category', 'brand', 'material', 'images', 'reviews'])
        ->whereHas('section', function($q) use ($sectionSlug) {
            $q->where('slug', $sectionSlug);
        })
        ->where('status', 'active');
       

    // Filter by category
    if ($request->has('category') && $request->category) {
        $query->where('category_id', $request->category);
    }

    // Filter by brand
    if ($request->has('brand') && $request->brand) {
        $query->where('brand_id', $request->brand);
    }

    // Filter by material
    if ($request->has('material') && $request->material) {
        $query->where('material_id', $request->material);
    }

    // Filter by price
    if ($request->has('min_price') && $request->min_price) {
        $query->where('price', '>=', $request->min_price);
    }
    if ($request->has('max_price') && $request->max_price) {
        $query->where('price', '<=', $request->max_price);
    }

    // Filter by price range
    if ($request->has('price_range') && $request->price_range) {
        $priceRange = explode('-', $request->price_range);
        if (count($priceRange) == 2) {
            $query->whereBetween('price', [$priceRange[0], $priceRange[1]]);
        }
    }

    // Filter by stock range  
    if ($request->has('stock_range') && $request->stock_range) {
        $stockRange = explode('-', $request->stock_range);
        if (count($stockRange) == 2) {
            $query->whereBetween('stock', [$stockRange[0], $stockRange[1]]);
        }
    }

    // Filter by status
    if ($request->has('status') && $request->status) {
        $query->where('status', $request->status);
    }

    // Search by name
    if ($request->has('search') && $request->search) {
        $query->where('name', 'like', '%' . $request->search . '%');
    }

    // sắp xếp sản phẩm 
    $sortBy = $request->get('sort_by', 'name');
    $sortOrder = $request->get('sort_order', 'asc');
    
    switch ($sortBy) {
        case 'price':
            $query->orderBy('price', $sortOrder);
            break;
        case 'name':
            $query->orderBy('name', $sortOrder);
            break;
        case 'created_at':
            $query->orderBy('created_at', $sortOrder);
            break;
        default:
            $query->orderBy('name', 'asc');
    }

    $products = $query->paginate(12);

    // Lấy dữ liệu filter
    $categories = Category::where('section_id', $section->id)->get();
    $brands = Brand::all();
    $materials = Material::all();

    return view('pages.products.section', compact(
        'products', 'section', 'categories', 'brands', 'materials'
    ));
}


    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        $product->load(['section', 'category', 'brand', 'material', 'images', 'reviews' => function($query) {
            $query->where('approved', true)->orderBy('created_at', 'desc');
        }]);

        // Get related products
        $relatedProducts = Product::with(['section', 'category', 'brand', 'material', 'images'])
            ->where('section_id', $product->section_id)
            ->where('id', '!=', $product->id)
            ->where('status', 'active')
            ->limit(4)
            ->get();

        return view('pages.products.show', compact('product', 'relatedProducts'));
    }
}
