<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
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

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Users, Admins, BrandAdmins
        User::factory(10)->create();
        Admin::factory(3)->create();
        Brand::factory(5)->create(); // لازم قبل BrandAdmin علشان العلاقة
        BrandAdmin::factory(5)->create();

        // Categories & SubCategories
        Category::factory(5)->create()->each(function ($category) {
            SubCategory::factory(3)->create([
                'category_id' => $category->id
            ]);
        });

        // Brands & Products & Images
        Product::factory(20)->create()->each(function ($product) {
            ProductImage::factory(3)->create([
                'product_id' => $product->id
            ]);
        });

        // Coupons & Cities
        City::factory(5)->create();

        // Wishlists
        UserWishlist::factory(15)->create();

        // Orders & OrderItems & OrderDetails
        Order::factory(10)->create()->each(function ($order) {
            OrderItem::factory(3)->create([
                'order_id' => $order->id
            ]);
        });

        OrderDetail::factory(10)->create();

        // Carts & Cart Items
        Cart::factory(10)->create()->each(function ($cart) {
            CartItem::factory(2)->create([
                'cart_id' => $cart->id
            ]);
        });
    }
}
