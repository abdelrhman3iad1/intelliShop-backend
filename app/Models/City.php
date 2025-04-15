<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'shipping_charges'];

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
}
