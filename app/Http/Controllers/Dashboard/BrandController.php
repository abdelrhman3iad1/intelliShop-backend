<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\BrandAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:brands,name',
            'description' => 'required|string',
            'logo' => 'required|image|mimes:png,jpeg,jpg,webp',
            'cover' => 'required|image|mimes:png,jpeg,jpg,webp',
            'organization_license' => 'required|image|mimes:png,jpeg,jpg,webp',
            'commercial_registry_extract' => 'required|image|mimes:png,jpeg,jpg,webp',
            'tax_registry' => 'required|image|mimes:png,jpeg,jpg,webp',
            "owner_name" => "required|string|max:255",
            "owner_email" => "required|email|max:255|string|unique:brand_admins,email",
            "password" => ["required", "min:8", "confirmed", Password::defaults()]
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                "message" => "Validation Error",
                "data" => $validator->errors(),
            ], 400);
        }

        $brand = new Brand();
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name);
        $brand->description = $request->description;
        $brand->logo = 'Uploads/' . $request->file('logo')->storePublicly('Brand/Logo', 'public');
        $brand->cover = 'Uploads/' . $request->file('cover')->storePublicly('Brand/Cover', 'public');
        $brand->organization_license = 'Uploads/' . $request->file('organization_license')->storePublicly('Brand/Organization_license', 'public');
        $brand->commercial_registry_extract = 'Uploads/' . $request->file('commercial_registry_extract')->storePublicly('Brand/Commercial_registry_extract', 'public');
        $brand->tax_registry = 'Uploads/' . $request->file('tax_registry')->storePublicly('Brand/Tax_registry', 'public');
        $brand->save();

        $brand_admin = new BrandAdmin();
        $brand_admin->name = $request->owner_name;
        $brand_admin->email = $request->owner_email;
        $brand_admin->password = Hash::make($request->password);
        $brand_admin->is_super_brand_admin = 1;
        $brand_admin->brand_id = $brand->id;
        $brand_admin->save();

        return response()->json([
            "status" => true,
            "message" => "Form Submitted , Wait for Confirmation",
            "data" => null,
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Brand $brand)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Brand $brand)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Brand $brand)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand)
    {
        //
    }
}
