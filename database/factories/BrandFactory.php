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
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\=Brand>
 */
class BrandFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
        'slug' => $this->faker->slug,
        'description' => $this->faker->paragraph,
        'logo' => $this->faker->imageUrl(),
        'cover' => $this->faker->imageUrl(),
        'status' => $this->faker->boolean(),
        'organization_license' => $this->faker->imageUrl,
        'commercial_registry_extract' => $this->faker->imageUrl,
        'tax_registry' => $this->faker->imageUrl,
        ];
    }
}
