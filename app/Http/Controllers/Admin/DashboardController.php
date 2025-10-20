<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Material;
use App\Models\Offer;
use App\Models\Review;
use App\Models\Contact;
use App\Models\VisitorStat;
use App\Models\User;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        $stats = [
            'total_products'    => Product::count(),
            'active_products'   => Product::where('status', 'active')->count(),
            'featured_products' => Product::where('featured', true)->count(),
            'total_categories'  => Category::count(),
            'total_brands'      => Brand::count(),
            'total_materials'   => Material::count(),
            'active_offers'     => Offer::where('status', 'active')->count(),
            'total_reviews'     => Review::count(),
            'pending_reviews'   => Review::where('approved', false)->count(),
            'total_contacts'    => Contact::count(),
            'new_contacts'      => Contact::where('status', 'new')->count(),
            'total_visitors'    => VisitorStat::sum('total_visits'),
            'unique_visitors'   => VisitorStat::sum('unique_visits'),
            'total_users'       => User::count(), // ✅ thêm dòng này
        ];

        // Recent activities
        $recentProducts = Product::with(['section', 'category', 'brand'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recentReviews = Review::with('product')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recentContacts = Contact::orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Visitor stats for chart (last 7 days)
        $visitorStats = VisitorStat::orderBy('date', 'desc')
            ->limit(7)
            ->get()
            ->reverse();

        $recentUsers = User::with('role'    ) // <- thêm with('role') để load quan hệ role
                    ->latest()
                    ->take(5)
                    ->get();

        return view('admin.dashboard', compact('stats', 'recentProducts', 'recentReviews', 'recentContacts', 'visitorStats', 'recentUsers'));
    }
}
