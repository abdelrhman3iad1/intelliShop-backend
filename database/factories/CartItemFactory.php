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
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\=CartItem>
 */
class CartItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
        'product_name' => $this->faker->word,
        'product_price' => $this->faker->randomFloat(2, 10, 100),
        'brand_id' => Brand::factory(),
        'quantity' => $this->faker->numberBetween(1, 10),
        'cart_id' => Cart::factory(),
        ];
    }
}
