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

        // Initialize breadcrumbs array
        $breadcrumbs = [];

        // If a category slug is provided, filter the categories to show only the relevant subtree
        if ($cat_slug) {
            $selectedCategory = $this->findCategoryBySlug($categories, $cat_slug);
            if ($selectedCategory) {
                // Build breadcrumbs for the selected category
                $breadcrumbs = $this->buildBreadcrumbs($categories, $selectedCategory->id);
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

        // Pass the organized categories data and breadcrumbs to the view for display
        return view('admin.basic.category', compact('hierarchicalCategories', 'categories', 'breadcrumbs'));
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

    protected function findCategoryById($categories, $categoryId)
    {
        foreach ($categories as $category) {
            if ($category->id == $categoryId) {
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

    protected function buildBreadcrumbs($categories, $categoryId)
    {
        $breadcrumbs = [];

        $category = $this->findCategoryById($categories, $categoryId);

        while ($category) {
            // Add the category to the beginning of the breadcrumbs array
            array_unshift($breadcrumbs, $category);
            // Move to the parent category
            $category = $this->findCategoryById($categories, $category->parent_id);
        }

        return $breadcrumbs;
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
        $category = Category::findOrFail($id);

        return response()->json($category, 200);
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

        // Find the Category by ID
        $category = Category::findOrFail($id);

        // Generate a unique slug for the category based on the name
        $originalSlug = Str::slug($request->name);
        $catSlug = $originalSlug;
        $counter = 1;

        // Check for slug uniqueness
        while (Category::where('cat_slug', $catSlug)->exists()) {
            $catSlug = $originalSlug . '-' . $counter;
            $counter++;
        }

        // Update the category details
        $category->cat_name = $request->name;
        $category->cat_slug = $catSlug;
        $category->status = $request->status;

        // Update the image if a new one is provided
        if ($request->hasFile('image')) {
            // Delete old image(s) if any
            ImageHelper::deleteImage($category->cat_img);

            // Upload the new image and get the image path
            $imagePath = ImageHelper::uploadImage($request->file('image'), 'category');

            // Update the category's image path
            $category->cat_img = $imagePath;
        }

        // Save the updated category
        $category->save();

        return response()->json([
            'option' => $category,
            'type' => 'success',
            'text' => 'Category has been updated successfully',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find the category by ID
        $category = Category::findOrFail($id);

        // Delete the category and its children
        $this->deleteCategoryAndChildren($category);

        // Redirect or return a response as needed
        return response()->json([
            'type' => 'success',
            'text' => 'Category has been deleted successfully',
        ], 200);
    }

    protected function deleteCategoryAndChildren($category)
    {
        // Delete children categories recursively
        if ($category->children) {
            foreach ($category->children as $child) {
                $this->deleteCategoryAndChildren($child);
            }
        }

        // Delete associated images
        ImageHelper::deleteImage($category->cat_img);

        // Delete the current category
        $category->delete();
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
