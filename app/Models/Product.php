<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\Auditable;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Auditable;

    protected $fillable = [
        'name',
        'description',
        'full_description',
        'section_id',
        'category_id',
        'brand_id',
        'material_id',
        'price',
        'sale_price',
        'stock',
        'slug',
        'featured',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'featured' => 'boolean',
    ];

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }

    public function offers(): BelongsToMany
    {
        
        return $this->belongsToMany(Offer::class, 'offer_products');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function homeSections()
    {
        return $this->belongsToMany(HomeProductSection::class, 'home_product_section_product','product_id','home_product_section_id');
    }

    public function getPrimaryImageAttribute()
    {
        return $this->images()->where('is_primary', true)->first();
    }

    public function getAverageRatingAttribute()
    {
        return $this->reviews()->where('approved', true)->avg('rating') ?? 0;
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }


    // Get the users that favorited the product
    public function favoredByUsers()
    {
        return $this->belongsToMany(User::class, 'favorites', 'product_id', 'user_id')
                    ->withTimestamps();
    }

}
