<?php

namespace App\Http\Controllers\Admin\Basic;

use App\Http\Controllers\Controller;
use App\Models\Admin\Brand;
use Illuminate\Http\Request;
use App\Helpers\ImageHelper;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Retrieve brands from the database
        $brands = Brand::all();

        // Pass the brands data to the view for display
        return view('admin.basic.brand', compact('brands'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'status' => 'required|in:1,0',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Upload image(s) and get the image path(s)
        $imagePaths = ImageHelper::uploadImage($request->file('image'));

        // Create a new Brand instance with the image path(s)
        $brand = Brand::create([
            'brand_name' => $request->name,
            'status' => $request->status,
            'brand_img' => $imagePaths,
            'created_by' => auth('admin')->user()->id,
        ]);

        return response()->json([
            'option' => $brand,
            'type' => 'success',
            'text' => 'Brand has been added Successfully',
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $brand = Brand::findOrFail($id);

        return response()->json($brand, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string',
            'status' => 'required|in:1,0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Find the Brand by ID
        $brand = Brand::findOrFail($id);

        // Update the brand details
        $brand->brand_name = $request->input('name');
        $brand->status = $request->status;

        // Update the image if a new one is provided
        if ($request->hasFile('image')) {
            // Delete old image(s) if any
            ImageHelper::deleteImage($brand->brand_img);

            // Upload the new image and get the image path
            $imagePath = ImageHelper::uploadImage($request->file('image'));

            // Update the brand's image path
            $brand->brand_img = $imagePath;
        }

        // Save the updated brand
        $brand->save();

        return response()->json([
            'option' => $brand,
            'type' => 'success',
            'text' => 'Brand has been updated successfully',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find the Brand by ID
        $brand = Brand::findOrFail($id);

        // Delete associated image(s)
        ImageHelper::deleteImage($brand->brand_img);

        // Delete the brand
        $brand->delete();

        return response()->json([
            'type' => 'success',
            'text' => 'Brand has been deleted successfully',
        ], 200);
    }

    public function uniqueBrandNmae(Request $request)
    {
        $brand = Brand::where([
            'brand_name' => $request->name,
        ])->first();

        if (empty($brand)) {
            $output = array('success' => true);
            return response()->json($output, 200);
        } elseif ($brand->id == $request->exception_data) { //in case of edit form
            $output = array('success' => true);
            return response()->json($output, 200);
        }
    }
}
