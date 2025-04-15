<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name', 'last_name', 'email', 'phone_number',
        'address_one', 'address_two', 'city_id', 'postal_code'
    ];

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
