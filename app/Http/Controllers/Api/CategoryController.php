<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function categories()
    {
        return response()->json([
            'categories' => Category::latest()->get()
        ], 200);
    }

    public function store_category(Request $request)
    {
        $request->validate([
            'name' => 'required | max: 80 | unique:categories'
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return response()->json([
            'message' => 'Category successfully created'
        ], 200);
    }

    public function fetch_single_category($id, $slug)
    {
        $category = Category::where([
            'id' => $id,
            'slug' => $slug
        ])
            ->with('posts')
            ->firstOrFail();

        return response()->json([
            'category' => $category
        ]);
    }

    public function update_category(Request $request)
    {
        $category = Category::whereId($request->id)->firstOrFail();

        $request->validate([
            'name' => 'required | max: 80 | unique:categories,name,' . $category->id
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return response()->json([
            'message' => 'Category successfully deleted'
        ], 200);
    }

    public function delete_category($id)
    {
        $category = Category::whereId($id)->with('posts')->firstOrFail();

        if (!$category->posts->count()) {

            $category->delete();

            return response()->json([
                'message' => 'Category Successfully Deleted'
            ], 200);
        }

        return response()->json([
            'error' => 'category cannot be deleted'
        ], 400);
    }
}
