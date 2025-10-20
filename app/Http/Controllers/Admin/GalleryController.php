<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductImage::with('product');

        // Search by product name
        if ($request->has('search') && $request->search != '') {
            $query->whereHas('product', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by product
        if ($request->has('product') && $request->product != '') {
            $query->where('product_id', $request->product);
        }

        // Filter by image type (primary/secondary)
        if ($request->has('is_primary') && $request->is_primary != '') {
            $query->where('is_primary', $request->is_primary == '1');
        }

        // Filter by creation date
        if ($request->has('created_from') && $request->created_from != '') {
            $query->whereDate('created_at', '>=', $request->created_from);
        }

        $query->orderBy('created_at', 'desc');
        $images = $query->paginate(20)->withQueryString();

        $products = \App\Models\Product::all();
        
        return view('admin.gallery.index', compact('images', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'images'     => 'required|array|max:10',
            'images.*'   => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'alt_text'   => 'nullable|string|max:255',
        ], [
            'product_id.required' => 'Vui lòng chọn sản phẩm.',
            'product_id.exists'   => 'Sản phẩm khong ton tai.',
            'images.required'     => 'Vui lòng chọn hình ảnh.',
            'images.array'        => 'Vui lòng chọn nhiều hình ảnh.',

        ]);

        $uploadedImages = [];

        foreach ($request->file('images') as $index => $file) {
            $filename = time() . '_' . $index . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('products', $filename, 'public');

            $image = ProductImage::create([
                'product_id' => $request->product_id,
                'url' => $filename,
                'alt_text' => $request->alt_text ?: $file->getClientOriginalName(),
                'is_primary' => $index === 0, // First image is primary
            ]);

            $uploadedImages[] = $image;
        }

        return redirect()->route('admin.gallery.index')->with('success', 'Images uploaded successfully!');
    }

    public function destroy(ProductImage $image)
    {
        // Soft delete, không xóa file ngay
        $image->delete();
        return redirect()->route('admin.gallery.index')->with('success', 'Image deleted successfully!');
    }

    public function setPrimary(ProductImage $image)
    {
        // Remove primary from other images of the same product
        ProductImage::where('product_id', $image->product_id)->update(['is_primary' => false]);
        
        // Set this image as primary
        $image->update(['is_primary' => true]);

        return redirect()->route('admin.gallery.index')->with('success', 'Primary image updated successfully!');
    }
}
