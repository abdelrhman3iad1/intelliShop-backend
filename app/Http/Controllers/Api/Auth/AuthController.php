<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\User;
use App\Http\Resources\UserResource;
class AuthController extends Controller
{
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            "name" => "required|string|max:255",
            "email" => "required|email|max:255|string|unique:users,email",
            "password" => ["required", "min:8", "confirmed", Password::defaults()]
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                "message" => "Validation Error",
                "data" => $validator->errors(),
            ], 400);
        }

        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password),
        ]);

        $token = $user->createToken("API TOKEN OF " . $user->name)->plainTextToken;

        return response()->json([
            "status" => true,
            "message" => "Registration Done Successfully.",
            "data" => new UserResource($user),
            "token" => $token
        ], 201);
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            "email" => "required|email|exists:users,email",
            "password" => "required|min:8"
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                "message" => "Validation Error",
                "data" => $validator->errors(),
            ], 400);
        }
    
        $user = User::where("email", $request->email)->first();
    
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                "message" => "Credentials not correct.",
                "status" => false,
                "data" => null
            ], 403);
        }
    
    
        $token = $user->createToken("API TOKEN OF " . $user->name)->plainTextToken;
    
        return response()->json([
            "status" => true,
            "message" => "Login Done Successfully." ,
            "data" => new UserResource($user),
            "token" =>$token
        ], 200);
    }

    public function logout(Request $request){
        if (!$request->user()) {
            return response()->json([
                'status'=>false,
                'message' => 'Unauthenticated',
                'data'=>null,
            ], 401);
        }

        $request->user()->tokens()->delete();

        return response()->json([
            'status'=>true,
            'message' => 'Successfully logged out',
            'data'=>null
        ], 200);
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required|string|min:8',
            'password' => ['nullable', 'string', 'min:8', 'confirmed', Password::defaults()],
            'email' => 'nullable|email|unique:users,email,' . $request->user()->id,
            'name' => 'nullable|string|max:255',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'data' => $validator->errors(),
            ], 400);
        }
    
        $user = $request->user();
    
        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Old password is incorrect.',
                'data' => null,
            ], 403);
        }
    
        $updateData = [];
    
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }
    
        if ($request->filled('email')) {
            $updateData['email'] = $request->email;
        }
    
        if ($request->filled('name')) {
            $updateData['name'] = $request->name;
        }
    
        if (empty($updateData)) {
            return response()->json([
                'status' => false,
                'message' => 'No data provided to update.',
            ], 400);
        }
    
        $user->update($updateData);
    
        return response()->json([
            'status' => true,
            'message' => 'Profile updated successfully.',
            'data' => new UserResource($user),
        ], 200);
    }
    
}
