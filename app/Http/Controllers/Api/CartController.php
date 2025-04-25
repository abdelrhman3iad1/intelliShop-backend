<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CartItemResource;
use App\Http\Resources\CartResource;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        if($validator->fails()){
            return response()->json([
                'status'=>false,
                'message'=>'Validation Error',
                'data'=>$validator->errors()
            ],400);
        }
        $user = $request->user();

        $product = Product::findOrFail($request->product_id);

        if($request->quantity > $product->quantity){
            return response()->json([
                'status'=>false,
                'message'=>'Quantity Not Enough',
            ],400);
        }

        $cart = Cart::firstOrCreate(['user_id' => $user->id]);



        $cartItem = CartItem::updateOrCreate(
            ['cart_id' => $cart->id, 'product_id' => $product->id],
            [
                'product_name' => $product->name,
                'product_price' => $product->total_price,
                'quantity' => $request->quantity,
                'total_price' => $request->quantity * $product->total_price,
                'brand_id' => $product->brand_id
            ]
        );

        return response()->json([
            'status' => true,
            'message' => 'Product added to cart successfully.',
            'data' => new CartItemResource($cartItem)
        ], 200);
    }

    public function removeFromCart(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'product_id' => 'required|exists:products,id',
        ]);

        if($validator->fails()){
            return response()->json([
                'status'=>false,
                'message'=>'Validation Error',
                'data'=>$validator->errors()
            ],400);
        }

        $user = $request->user();
        $cart = Cart::where('user_id', $user->id)->first();

        if (!$cart) {
            return response()->json(['status' => false, 'message' => 'Cart not found.'], 404);
        }

        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $request->product_id)
            ->first();

        if (!$cartItem) {
            return response()->json(['status' => false, 'message' => 'Product not found in cart.'], 404);
        }

        $cartItem->delete();

        return response()->json(['status' => true, 'message' => 'Product removed from cart.'], 200);
    }

    public function deleteCart(Request $request)
    {
        $user = $request->user();
        $cart = Cart::where('user_id', $user->id)->first();

        if (!$cart) {
            return response()->json(['status' => false, 'message' => 'Cart not found.'], 404);
        }

        $cart->cartItems()->delete();
        $cart->delete(); // delete cart

        return response()->json(['status' => true, 'message' => 'Cart deleted successfully.'], 200);
    }

    public function getCart(Request $request)
    {
        $user = $request->user;
        $cart = Cart::where('user_id', $user->id)->with('cartItems')->first();

        if (!$cart) {
            return response()->json(['status' => false, 'message' => 'Cart is empty'], 404);
        }

        return response()->json([
            'status' => true,
            'message'=>'Cart Retrived Successfully',
            'data' => new CartResource($cart)
        ],200);
    }
}
