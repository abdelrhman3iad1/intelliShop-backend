<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrandAdmin extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'password', 'is_super_brand_admin', 'brand_id'];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
}
