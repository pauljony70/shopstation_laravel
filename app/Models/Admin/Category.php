<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Category extends Model
{
    use HasFactory;

    protected $table = 'category';

    protected $fillable = [
        'cat_name',
        'cat_slug',
        'cat_img',
        'parent_id',
        'status',
        'created_by'
    ];

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id', 'id');
    }

    public static function getCategoryTree($parentId = 0, $status = [0, 1]): Collection
    {
        $categories = self::where('parent_id', $parentId)->whereIn('status', $status)->get();
        $categoryTree = self::buildCategoryTree($categories, $parentId);
        return collect($categoryTree);
    }

    public static function getBreadcrumbs($categoryId, $status = [0, 1]): array
    {
        $categories = self::whereIn('status', $status)->get();
        $breadcrumbs = self::buildBreadcrumbs($categories, $categoryId);
        return $breadcrumbs;
    }

    protected static function buildCategoryTree(Collection $categories, $parentId = 0): array
    {
        $categoryTree = [];

        foreach ($categories as $category) {
            if ($category->parent_id == $parentId) {
                $children = self::buildCategoryTree($categories, $category->id);

                if (!empty($children)) {
                    $category->children = $children;
                }

                $categoryTree[] = $category;
            }
        }

        return $categoryTree;
    }

    protected static function buildBreadcrumbs(Collection $categories, $categoryId): array
    {
        $breadcrumbs = [];
        $category = self::findCategoryById($categories, $categoryId);

        while ($category) {
            array_unshift($breadcrumbs, $category);
            $category = self::findCategoryById($categories, $category->parent_id);
        }

        return $breadcrumbs;
    }

    protected static function findCategoryById(Collection $categories, $categoryId)
    {
        return $categories->find($categoryId);
    }
}
