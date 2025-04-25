<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderDetails;
use App\Models\OrderItem;
use App\Models\OrderItems;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    protected int $shipping_charges;
    protected int $taxes;
    public function __construct(){
        $this->shipping_charges = config('payment.shipping_charges') ?? 0;
        $this->taxes = config('payment.tax')?? 0;
    }
    public function checkout(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(),[
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'required|string|max:20',
            'address_one' => 'required|string',
            'address_two' => 'nullable|string',
            'postal_code' => 'required|string|max:20',
        ]);

        if($validator->fails()){
            return response()->json([
                'status'=>false,
                'message'=>'Validation Error',
                'data'=>$validator->errors()
            ],400);
        }

        $cart = Cart::with('cartItems')->where('user_id', $user->id)->first();
        if (!$cart || $cart->items->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'Cart is empty.',
            ], 400);
        }

        DB::beginTransaction();
        try {
            $shipping = $this->shipping_charges;
            $tax = $this->taxes;
            $subtotal = $cart->cartItems->sum(fn($item) => $item->product_price * $item->quantity);
            $total = $subtotal + $this->shipping_charges + $tax ;

            $order = Order::create([
                'number' => 'ORD-' . strtoupper(Str::random(10)),
                'shipping_charges' => $shipping,
                'tax' => $tax,
                'total_price' => $total,
                'status' => 'pending',
                'user_id' => $user->id,
            ]);

            OrderDetail::create([
                'order_id' => $order->id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'address_one' => $request->address_one,
                'address_two' => $request->address_two,
                'postal_code' => $request->postal_code,
            ]);

            foreach ($cart->cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product_name,
                    'product_price' => $item->product_price,
                    'quantity' => $item->quantity,
                    'brand_id' => $item->brand_id,
                ]);
            }

            // حذف كل عناصر السلة
            $cart->items()->delete();
            $cart->delete();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Order placed successfully.',
                'order_number' => $order->number
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($order){
     
        $order = Order::find($order);
        if(!$order){
            return response()->json([
                'status' => false,
                'message' => 'Order Not Exist.',
            ],400);
        }
        $user = request()->user();

        if ($order->user_id !== $user->id) {
            return response()->json([
                'status' => false,
                'message' => 'You are not authorized to view this order.',
            ], 403);
        }

        return response()->json([
            'status'=>true,
            'message'=>'Order Retrived Successfully',
            'data'=> new OrderResource($order->load(['details', 'items'])),
        ],200);
    }

    public function index()
    {
        $user =  request()->user();

        $exists = Order::where('user_id',$user->id)->exists();
        if(!$exists){
            return response()->json([
                'status'=>false,
                'message'=>'No Order For this user',
            ],400);
        }
        $orders = $user->orders()->with(['details', 'items'])->latest()->get();

        return response()->json([
            'status'=>true,
            'message'=>'Orders Retrived Successfully',
            'data'=> OrderResource::collection($orders),
        ],200);
    }

    public function cancel($id)
{
    $user =  request()->user();

    $order = Order::where('user_id',$user->id)
    ->first();

    if(!$order){
        return response()->json([
            'status'=>false,
            'message'=>'No Order For this user',
        ],400);
    }

    if ($order->user_id !== $user->id) {
        return response()->json([
            'status' => false,
            'message' => "Order don't belong to this user",
        ], 403);
    }

    if ($order->status !== 'pending') {
        return response()->json([
            'status' => false,
            'message' => 'Cannot cancel a processed order.',
        ], 422);
    }

    $order->status = 'cancelled';
    $order->save();

    return response()->json([
        'status' => true,
        'message' => 'Order cancelled successfully.',
        'order' => new OrderResource($order),
    ]);
}
}
