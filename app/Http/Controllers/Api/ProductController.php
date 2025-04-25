<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use App\Http\Resources\ProductResource;
class ProductController extends Controller
{
    public function index(){
        $products = Product::orderBy('created_at','desc')->paginate(10);

        if($products->count()== 0){
            return response()->json([
                "success"=>false,
                "message"=>"No Products Exist",
                "data"=>null
            ],200);
        }

        return response()->json([
                "success"=>true,
                "message"=>"Products Retrived Successfully",
                "links" => [
                    'first' => $products->url(1),
                    'last' => $products->url($products->lastPage()),
                    'prev' => $products->previousPageUrl(),
                    'next' => $products->nextPageUrl()
            ],
                "data"=> ProductResource::collection($products)
        ],200);
    }

    public function getProduct($slug){
        $product = Product::with('brand')->where('slug',$slug)->first();

        if(!$product){
            return response()->json([
                "success"=>false,
                "message"=>"This Product Not Exist",
                "data"=>null
            ],404);
        }

        return response()->json([
                "success"=>true,
                "message"=>"Product Retrived Successfully",
                "data"=> new ProductResource($product)
        ],200);
    }

    public function getProductsByCategory($category_slug){
        $category = Category::where('slug', $category_slug)->first();

        if(!$category){
            return response()->json([
                "success"=>false,
                "message"=>"This Category Not Exist",
                "data"=>null
            ],404);
        }

        $products = $category->products()->latest()->paginate(10);

        if($products->count() == 0){
            return response()->json([
                "success"=>false,
                "message"=>"No Products Exist",
                "data"=>null
            ],200);
        }

        return response()->json([
            "success"=>true,
            "message"=>"Products Retrived Successfully",
            "links" => [
                'first' => $products->url(1),
                'last' => $products->url($products->lastPage()),
                'prev' => $products->previousPageUrl(),
                'next' => $products->nextPageUrl()  
            ],
            "data"=> ProductResource::collection($products),
    ],200);
    }

    public function getProductsByCategoryAndSubCat($category_slug , $subCategory_slug){
        $category = Category::where('slug',$category_slug)->first();

        if(!$category){
            return response()->json([
                "success"=>false,
                "message"=>"This Category Not Exist",
                "data"=>null
            ],404);
        }

        $sub_category = SubCategory::with('products')
        ->where('slug', $subCategory_slug)
        ->where('category_id',$category->id)
        ->first();

        if(!$sub_category){
            return response()->json([
                "success"=>false,
                "message"=>"This Sub Category Not Correct",
                "data"=>null
            ],404);
        }

        $products = $sub_category->products()->latest()->paginate(10);

        if($products->count() == 0){
            return response()->json([
                "success"=>false,
                "message"=>"No Products Exist",
                "data"=>null
            ],200);
        }

        return response()->json([
            "success"=>true,
            "message"=>"Products Retrived Successfully",
            "links" => [
                'first' => $products->url(1),
                'last' => $products->url($products->lastPage()),
                'prev' => $products->previousPageUrl(),
                'next' => $products->nextPageUrl()  
            ],
            "data"=> ProductResource::collection($products),
    ],200);
    }

    public function getProductsByBrand($brand_slug){
        $Brand = Brand::where('slug', $brand_slug)->first();

        if(!$Brand){
            return response()->json([
                "success"=>false,
                "message"=>"This Brand Not Exist",
                "data"=>null
            ],404);
        }

        $products = $Brand->products()->latest()->paginate(10);

        if($products->count() == 0){
            return response()->json([
                "success"=>false,
                "message"=>"No Products Exist",
                "data"=>null
            ],200);
        }

        return response()->json([
            "success"=>true,
            "message"=>"Products Retrived Successfully",
            "links" => [
                'first' => $products->url(1),
                'last' => $products->url($products->lastPage()),
                'prev' => $products->previousPageUrl(),
                'next' => $products->nextPageUrl()  
            ],
            "data"=> ProductResource::collection($products),
    ],200);
    }
}
