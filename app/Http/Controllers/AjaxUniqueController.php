<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin\Attribute;
use App\Models\Admin\AttributeSet;
use App\Models\Admin\AttributeValue;

class AjaxUniqueController extends Controller
{
    public function uniqueAttributeName(Request $request)
    {
        $attribute = Attribute::where([
            'attribute' => $request->name,
        ])->first();

        if (empty($attribute)) {
            $output = array('success' => true);
            return response()->json($output, 200);
        } elseif ($attribute->id == $request->exception_data) { //in case of edit form
            $output = array('success' => true);
            return response()->json($output, 200);
        }
    }

    public function uniqueAttributeValueName(Request $request)
    {
        $attribute_value = AttributeValue::where([
            'attribute_value' => $request->name,
        ])->first();

        if (empty($attribute_value)) {
            $output = array('success' => true);
            return response()->json($output, 200);
        } elseif ($attribute_value->id == $request->exception_data) { //in case of edit form
            $output = array('success' => true);
            return response()->json($output, 200);
        }
    }

    public function uniqueAttributeSetName(Request $request)
    {
        $attribute_set = AttributeSet::where([
            'name' => $request->name,
        ])->first();

        if (empty($attribute_set)) {
            $output = array('success' => true);
            return response()->json($output, 200);
        } elseif ($attribute_set->id == $request->exception_data) { //in case of edit form
            $output = array('success' => true);
            return response()->json($output, 200);
        }
    }
}
