<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PostController extends Controller
{

    public function all_posts()
    {
        return response()->json([
            'posts' => Post::with(['user', 'categories'])->latest()->get()
        ], 200);
    }

    public function categories()
    {
        return response()->json([
            'categories' => Category::all()
        ], 200);
    }

    public function store_post(Request $request)
    {
        $request->validate([
            'title' => 'required | string | max: 255',
            'body' => 'required',
            'categories' => 'required',
            'name' => 'max: 100 | unique:categories'
        ]);

        $post = Post::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . time(),
            'body' => $request->body,
            'user_id' => auth()->user()->id
        ]);

        $post->categories()->attach($request->categories);

        if ($request->name) {
            $category = Category::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
            ]);

            $post->categories()->attach($category->id);
        }

        return response()->json([
            'message' => 'Post Successfully Stored'
        ], 200);
    }

    public function fetch_single_post($id, $slug)
    {
        $post = Post::where([
            'id' => $id,
            'slug' => $slug
        ])
            ->with([
                'user',
                'categories'
            ])
            ->firstOrFail();

        return response()->json([
            'post' => $post,
            'categories' => $post->categories->pluck('id')
        ]);
    }

    public function update_post(Request $request)
    {
        $request->validate([
            'title' => 'required | string | max: 255',
            'body' => 'required',
            'categories' => 'required',
            'name' => 'max: 100 | unique:categories'
        ]);

        $post = Post::whereId($request->id)->firstOrFail();

        $post->categories()->detach();

        $post->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . time(),
            'body' => $request->body,
        ]);

        $post->categories()->attach($request->categories);

        if ($request->name) {
            $category = Category::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
            ]);

            $post->categories()->attach($category->id);
        }

        return response()->json([
            'message' => 'Post Successfully Updated'
        ], 200);
    }

    public function delete_post($id)
    {
        $post = Post::whereId($id)->firstOrFail();
        $post->categories()->detach();
        $post->delete();

        return response()->json([
            'message' => 'Post Successfully Deleted'
        ], 200);
    }
}
