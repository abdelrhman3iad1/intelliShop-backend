<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Admin;
use App\Models\BrandAdmin;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Coupon;
use App\Models\UserWishlist;
use App\Models\City;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderDetail;
use App\Models\Cart;
use App\Models\CartItem;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\=Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'number' => $this->faker->unique()->numerify('ORD###'),
        'shipping_charges' => $this->faker->randomFloat(2, 10, 100),
        'tax' => $this->faker->randomFloat(2, 1, 20),
        'discount' => $this->faker->randomFloat(2, 0, 30),
        'total_price' => $this->faker->randomFloat(2, 50, 999),
        'status' => 'pending',
        'user_id' => User::factory(),
        ];
    }
}
