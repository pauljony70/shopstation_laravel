<?php

namespace App\Http\Controllers\Admin\Basic;

use App\Http\Controllers\Controller;
use App\Models\Admin\AttributeValue;
use Illuminate\Http\Request;


class AttributeValueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Retrieve attribute_values from the database
        $attribute_values = AttributeValue::all();

        // Pass the attribute_values data to the view for display
        return view('admin.basic.attribute_value', compact('attribute_values'));
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
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
