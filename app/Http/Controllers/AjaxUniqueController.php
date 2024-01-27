<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin\Attribute;

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
}
