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
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\=Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
           'name' => $this->faker->word,
        'slug' => $this->faker->slug,
        'description' => $this->faker->text,
        'image' => $this->faker->imageUrl(),
        'price' => $this->faker->randomFloat(2, 10, 999),
        'quantity' => $this->faker->numberBetween(1, 100),
        'condition' => $this->faker->randomElement(['default', 'new', 'hot']),
        'status' => $this->faker->boolean(),
        'rating' => $this->faker->numberBetween(1, 5),
        'category_id' => Category::factory(),
        'sub_category_id' => SubCategory::factory(),
        'brand_id' => Brand::factory(),
        ];
    }
}
