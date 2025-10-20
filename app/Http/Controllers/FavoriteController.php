<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;

class FavoriteController extends Controller
{
    public function toggle(Product $product)
    {
        $user = auth()->user();

        $favorite = $user->favorites()->where('product_id', $product->id)->first();

        if ($favorite) {
            $favorite->delete();
            return response()->json(['success' => true, 'message' => 'Đã xóa khỏi danh sách yêu thích.']);
        } else {
            $user->favorites()->create(['product_id' => $product->id]);
            return response()->json(['success' => true, 'message' => 'Đã thêm vào danh sách yêu thích.']);
        }
    }
}
