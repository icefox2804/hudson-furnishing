<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
    ];

    // Quan hệ với Product
    public function product()
    {
        return $this->belongsTo(Product::class)->withDefault();
    }

    // Quan hệ với User (nếu cần)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
