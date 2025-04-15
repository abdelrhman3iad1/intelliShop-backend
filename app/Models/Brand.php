<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'description', 'logo', 'cover', 'status',
        'organization_license', 'commercial_registry_extract', 'tax_registry', 'wallet_id'
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
