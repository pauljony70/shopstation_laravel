<?php

namespace App\Http\Controllers\Admin\Basic;

use App\Http\Controllers\Controller;
use App\Models\Admin\Attribute;
use App\Models\Admin\AttributeValue;
use Illuminate\Http\Request;


class AttributeValueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Check if 'id' is available in the request
        if ($request->has('id')) {
            // Retrieve the Attribute by 'id'
            $attribute = Attribute::find($request->id);

            // If Attribute not found, show 404
            if (!$attribute) {
                abort(404);
            }

            // Retrieve AttributeValues where 'attribute_id' matches the request 'id'
            $attribute_values = AttributeValue::where('attribute_id', $request->id)->get();

            // Pass the data to the view for display
            return view('admin.basic.attribute_value', compact('attribute', 'attribute_values'));
        } else {
            // 'id' not provided in the request, show 404
            abort(404);
        }
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
            'id' => 'required|integer',
            'name' => 'required|string|unique:product_attributes_conf,attribute_value',
        ]);

        // Create a new Attribute Value instance
        $attribute_value = AttributeValue::create([
            'attribute_id' => $request->id,
            'attribute_value' => $request->name,
        ]);

        return response()->json([
            'option' => $attribute_value,
            'type' => 'success',
            'text' => 'Attribute value has been added Successfully',
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
        $attribute_value = AttributeValue::findOrFail($id);

        return response()->json($attribute_value, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|unique:product_attributes_conf,attribute_value,' . $id,
        ]);

        // Find the Attribute Value by ID
        $attribute_value = AttributeValue::findOrFail($id);

        // Update the attribute value details
        $attribute_value->attribute_value = $request->name;


        // Save the updated attribute value
        $attribute_value->save();

        return response()->json([
            'option' => $attribute_value,
            'type' => 'success',
            'text' => 'Attribute value has been updated successfully',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find the Attribute Value by ID
        $attribute_value = AttributeValue::findOrFail($id);

        // Delete the attribute value
        $attribute_value->delete();

        return response()->json([
            'type' => 'success',
            'text' => 'Attribute value has been deleted successfully',
        ], 200);
    }
}
