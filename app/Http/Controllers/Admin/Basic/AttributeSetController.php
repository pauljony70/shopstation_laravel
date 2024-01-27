<?php

namespace App\Http\Controllers\Admin\Basic;

use App\Http\Controllers\Controller;
use App\Models\Admin\AttributeSet;
use App\Models\Admin\Attribute;
use App\Models\Admin\AttributeSetProductInfo;
use Illuminate\Http\Request;

class AttributeSetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Retrieve attribute sets from the database
        $attribute_sets = AttributeSet::whereIn('status', ['0', '1'])->get();

        // Retrieve attributes from the database
        $attributes = Attribute::where('status', '1')->get();

        // Pass the attribute sets data to the view for display
        return view('admin.basic.attribute_set', compact('attribute_sets', 'attributes'));
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
            'name' => 'required|string|unique:attribute_set',
            'status' => 'required|in:1,0',
            'attribute_ids' => 'required|array',
            'attribute_ids.*' => 'required|integer',
        ]);

        // Create a new Attribute set instance with the image path(s)
        $attribute_set = AttributeSet::create([
            'name' => $request->name,
            'status' => $request->status,
            'created_by' => auth('admin')->user()->id,
        ]);

        // Insert attributes into attribute_set_attributes table
        $attributeSetId = $attribute_set->id;

        foreach ($request->attribute_ids as $productInfoSetId) {
            AttributeSetProductInfo::create([
                'attribute_set_id' => $attributeSetId,
                'attribute_id' => $productInfoSetId,
            ]);
        }

        return response()->json([
            'option' => $attribute_set,
            'type' => 'success',
            'text' => 'Attribute set has been added Successfully',
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
        // Find the attribute set
        $attribute_set = AttributeSet::findOrFail($id);

        // Get the attribute_ids for the given attribute set
        $attribute_ids = AttributeSetProductInfo::where('attribute_set_id', $id)
            ->pluck('attribute_id')
            ->toArray();

        // You can attach the attribute_ids to the attribute set if needed
        $attribute_set->attribute_ids = $attribute_ids;

        return response()->json($attribute_set, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string',
            'status' => 'required|in:1,0',
            'attribute_ids' => 'required|array',
            'attribute_ids.*' => 'required|integer',
        ]);

        // Find the Attribute set by ID
        $attribute_set = AttributeSet::findOrFail($id);

        // Update the attribute set details
        $attribute_set->name = $request->name;
        $attribute_set->status = $request->status;

        // Save the updated attribute set
        $attribute_set->save();

        // Sync the attribute_ids in the attribute_set_attributes table
        $attribute_set->attributeSetAttributes()->sync($request->attribute_ids);

        return response()->json([
            'option' => $attribute_set,
            'type' => 'success',
            'text' => 'Attribute set has been updated successfully',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find the attribute set by ID
        $attribute_set = AttributeSet::findOrFail($id);

        // Detach (remove) the related records in the attribute_set_attributes table
        $attribute_set->attributeSetAttributes()->detach();

        // Delete the attribute set
        $attribute_set->delete();

        return response()->json([
            'type' => 'success',
            'text' => 'Attribute set has been deleted successfully',
        ], 200);
    }
}
