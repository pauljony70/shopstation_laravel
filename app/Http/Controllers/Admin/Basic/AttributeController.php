<?php

namespace App\Http\Controllers\Admin\Basic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Attribute;

class AttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Retrieve attributes with status either 1 or 0 from the database
        $attributes = Attribute::whereIn('status', ['0', '1'])->get();

        // Pass the attributes data to the view for display
        return view('admin.basic.attributes', compact('attributes'));
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
        ]);

        // Create a new Attribute instance
        $attribute = Attribute::create([
            'attribute' => $request->name,
            'status' => $request->status,
            'created_by' => auth('admin')->user()->id,
        ]);

        return response()->json([
            'option' => $attribute,
            'type' => 'success',
            'text' => 'Attribute has been added Successfully',
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
        $attribute = Attribute::findOrFail($id);

        return response()->json($attribute, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string',
            'status' => 'required|in:1,0',
        ]);

        // Find the Attribute by ID
        $attribute = Attribute::findOrFail($id);

        // Update the attribute details
        $attribute->attribute = $request->name;
        $attribute->status = $request->status;

        // Save the updated attribute
        $attribute->save();

        return response()->json([
            'option' => $attribute,
            'type' => 'success',
            'text' => 'Attribute has been updated successfully',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find the Attribute by ID
        $attribute = Attribute::findOrFail($id);

        // Delete the attribute
        $attribute->delete();

        return response()->json([
            'type' => 'success',
            'text' => 'Attribute has been deleted successfully',
        ], 200);
    }
}
