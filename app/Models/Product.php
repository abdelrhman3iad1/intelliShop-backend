<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'description', 'image', 'price', 'quantity',
        'condition', 'status',/* 'rating',*/ 'category_id', 'sub_category_id', 'brand_id'
    ];

    /*public function images()
    {
        return $this->hasMany(ProductImage::class);
    }*/

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
}
