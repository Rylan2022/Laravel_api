<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Post::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('title', 'like', "%{$search}%")->orWhere('content', 'like', "%{$search}%");
        }
        return response()->json($query->paginate(10));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $id = null)
    {
        $rules = [
            'title' => ['required', Rule::unique('posts', 'title')->ignore($id)],
            'content' => 'required',
        ];


        $request->validate($rules, [
            'title.required' => 'A title is required not ok.',
            'title.unique'   => 'This title already exists.',
            'content.required' => 'Content cannot be empty.',
        ]);

        if ($id) {

            $post = Post::findOrFail($id);
            $post->update($request->all());

            return response()->json(['message' => 'Post updated successfully', 'data' => $post]);
        } else {
            $post = Post::create($request->all());
            return response()->json(['message' => 'Post create successfully', 'data' => $post], 201);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return response()->json(Post::findOrFail($id));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //m
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Post::destroy($id);
        return response()->json(['message' => 'Post deleted successfully']);
    }
}
