<?php

namespace App\Http\Controllers\Admin\Basic;

use App\Http\Controllers\Controller;
use App\Models\Admin\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\ImageHelper;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($cat_slug = null)
    {
        // Retrieve categories where status is 1 or 0 from the database
        $categories = Category::whereIn('status', [0, 1])->get();

        // Organize all categories in a hierarchical structure
        $hierarchicalCategories = $this->buildCategoryTree($categories);

        // If a category slug is provided, filter the categories to show only the relevant subtree
        if ($cat_slug) {
            $selectedCategory = $this->findCategoryBySlug($categories, $cat_slug);
            if ($selectedCategory) {
                // Build a tree only for the selected category's subtree
                $categories = $this->buildCategoryTree($categories, $selectedCategory->id);
            } else {
                // If the provided slug does not match any category, return a 404 page
                abort(Response::HTTP_NOT_FOUND);
            }
        } else {
            // If no category slug is provided, return all parent categories
            $categories = $this->buildCategoryTree($categories, 0);
        }

        // Pass the organized categories data to the view for display
        return view('admin.basic.category', compact('hierarchicalCategories', 'categories'));
    }

    protected function findCategoryBySlug($categories, $slug)
    {
        foreach ($categories as $category) {
            if ($category->cat_slug == $slug) {
                return $category;
            }
        }

        return null;
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

            // Check the depth of the category hierarchy
            if (!$request->make_parent_cat) {
                $parentCategory = Category::find($request->selected_category);
                $depth = 1;

                while ($parentCategory && $parentCategory->parent_id !== 0) {
                    $depth++;
                    $parentCategory = Category::find($parentCategory->parent_id);
                }

                if ($depth > 2) {
                    // Category depth exceeds the allowed limit
                    return response()->json([
                        'type' => 'error',
                        'text' => 'You can only insert categories up to the third level.',
                    ], 422);
                }
            }

            // Generate a unique slug for the category based on the name
            $originalSlug = Str::slug($request->name);
            $catSlug = $originalSlug;
            $counter = 1;

            // Check for slug uniqueness
            while (Category::where('cat_slug', $catSlug)->exists()) {
                $catSlug = $originalSlug . '-' . $counter;
                $counter++;
            }

            // Upload image(s) and get the image path(s)
            $imagePaths = ImageHelper::uploadImage($request->file('image'), 'category');

            // Create a new Category instance with the image path(s)
            $category = Category::create([
                'cat_name' => $request->name,
                'cat_slug' => $catSlug,
                'cat_img' => $imagePaths,
                'parent_id' => $request->make_parent_cat ? 0 : $request->selected_category,
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
