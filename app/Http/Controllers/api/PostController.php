<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PostController extends Controller
{
    /**
     * Display a listing of posts with pagination, search, sorting.
     */
    public function index(Request $request)
    {
        $limit = (int) $request->get('limit', 10);
        $offset = (int) $request->get('offset', 0);
        $orderBy = $request->get('order_by', 'created_at');
        $orderDir = $request->get('order_dir', 'desc');

        $query = Post::query();

        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                  ->orWhere('content', 'like', "%{$request->search}%");
            });
        }

        $totalEntries = $query->count();

        $posts = $query->orderBy($orderBy, $orderDir)
            ->skip($offset)
            ->take($limit)
            ->get();

        $currentPage = intval(($offset / $limit) + 1);
        $totalPages = (int) ceil($totalEntries / $limit);
        $nextPage = $currentPage < $totalPages ? $currentPage + 1 : null;

        return response()->json([
            'meta' => [
                'total_entries' => $totalEntries,
                'total_pages'   => $totalPages,
                'current_page'  => $currentPage,
                'limit'         => $limit,
                'next_page'     => $nextPage,
            ],
            'data' => $posts
        ], 200);
    }

    /**
     * Store or update a post.
     */
    public function store(Request $request, $id = null)
    {
        $rules = [
            'title' => ['required', Rule::unique('posts', 'title')->ignore($id)],
            'content' => 'required',
        ];

        $request->validate($rules, [
            'title.required'  => 'A title is required.',
            'title.unique'    => 'This title already exists.',
            'content.required'=> 'Content cannot be empty.',
        ]);

        if ($id) {
            $post = Post::findOrFail($id);
            $post->update($request->all());
            return response()->json(['message' => 'Post updated successfully', 'data' => $post]);
        } else {
            $post = Post::create($request->all());
            return response()->json(['message' => 'Post created successfully', 'data' => $post], 201);
        }
    }

    /**
     * Display a single post.
     */
    public function show($id)
    {
        return response()->json(Post::findOrFail($id));
    }

    /**
     * Update a post (PUT).
     */
    public function update(Request $request, $id)
    {
        return $this->store($request, $id); // re-use store logic
    }

    /**
     * Delete a post.
     */
    public function destroy($id)
    {
        Post::destroy($id);
        return response()->json(['message' => 'Post deleted successfully']);
    }
}
