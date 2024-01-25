<?php

namespace App\Http\Controllers\Admin\Basic;

use App\Http\Controllers\Controller;
use App\Models\Admin\Category;
use Illuminate\Http\Request;
use App\Helpers\ImageHelper;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Retrieve all categories from the database
        $categories = Category::all();

        // Organize categories in a hierarchical structure
        $hierarchicalCategories = $this->buildCategoryTree($categories);

        // Pass the organized categories data to the view for display
        return view('admin.basic.category', compact('hierarchicalCategories'));
    }

    protected function buildCategoryTree($categories, $parentId = 0)
    {
        $categoryTree = [];

        foreach ($categories as $category) {
            if ($category->parent_id == $parentId) {
                $children = $this->buildCategoryTree($categories, $category->id);

                if ($children->isNotEmpty()) {
                    $category->children = $children;
                }

                $categoryTree[] = $category;
            }
        }

        return collect($categoryTree);
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
        try {
            $request->validate([
                'name' => 'required|string',
                'status' => 'required|in:1,0',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Upload image(s) and get the image path(s)
            $imagePaths = ImageHelper::uploadImage($request->file('image'), 'category');

            // Create a new Category instance with the image path(s)
            $category = Category::create([
                'cat_name' => $request->name,
                'cat_slug' => $request->name,
                'cat_img' => $imagePaths,
                'status' => $request->status,
                'created_by' => auth('admin')->user()->id,
            ]);

            return response()->json([
                'option' => $category,
                'type' => 'success',
                'text' => 'Category has been added Successfully',
            ], 200);
        } catch (\Exception $e) {
            // Delete the uploaded image if an error occurs
            if ($imagePaths) {
                ImageHelper::deleteImage($imagePaths);
            }

            return response()->json([
                'type' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
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

    public function uniqueCategoryNmae(Request $request)
    {
        $category = Category::where([
            'cat_name' => $request->name,
        ])->first();

        if (empty($category)) {
            $output = array('success' => true);
            return response()->json($output, 200);
        } elseif ($category->id == $request->exception_data) { //in case of edit form
            $output = array('success' => true);
            return response()->json($output, 200);
        }
    }
}
