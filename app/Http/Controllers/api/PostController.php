<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Helpers\ApiResponse;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PostController extends Controller 
{
    /**
     * Display a listing of posts with pagination, search, sorting.
     */
    public function index(Request $request)
    {
        try {
            $limit    = (int) $request->get('limit', 10);
            $offset   = (int) $request->get('offset', 0);
            $orderBy  = $request->get('order_by', 'id');
            $orderbyDesc  = $request->get('$orderbyDesc', 'desc');

            $query = Post::query('user');

            if ($request->has('search')) {
                $search = $request->input('search');
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('content', 'like', "%{$search}%");
                });
            }

            $totalEntries = $query->count();

            if ($totalEntries === 0) {
                return response()->json([
                    'error' => true,
                    'data' => "",
                    "message" => "Posts Not Found."
                ], 404);
            }

            $posts = $query->with('user')
                ->orderBy($orderBy, $orderbyDesc)
                ->skip($offset)
                ->take($limit)
                ->get();

            $currentPage = intval(($offset / $limit) + 1);
            $totalPages  = (int) ceil($totalEntries / $limit);
            $nextPage    = $currentPage < $totalPages ? $currentPage + 1 : null;

            return response()->json([
                    "status" =>"success",
                    "message" => "Posts retrieved successfully",
                    "data" => $posts,
                    "pagination" => [
                    'total_entries' => $totalEntries,
                    'total_pages'   => $totalPages,
                    'current_page'  => $currentPage,
                    'limit'         => $limit,
                    'next_page'     => $nextPage,
                    ]
                 ], 200);


        } catch (Exception $e) {
            return response()->json([
                'error' => true,
                'data' => '',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Store or update a post.
     */
    public function store(Request $request, $id = null)
    {
        DB::beginTransaction();
        try {
            $rules = [
                'title_data'   => ['required', Rule::unique('posts', 'title')->ignore($id)],
                'content_data' => 'required',
            ];

            $messages = [
                'title_data.required'   => 'A title is required.',
                'title_data.unique'     => 'This title already exists.',
                'content_data.required' => 'Content cannot be empty.',
            ];

            $request->validate($rules, $messages);

            $data = [
                'title'   => $request->input('title_data'),
                'content' => $request->input('content_data'),
                'user_id' => Auth::id(),
            ];

            if ($id) {
                $post = Post::findOrFail($id);
                if($post->user_id !== $request->user()->id){
                    DB::rollBack();
                    return response()->json([
                        'error' => true,
                        'message' => 'Unauthorized to update this post',
                    ],403);
                }


                $post->update($data);
                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => 'Post updated successfully',
                 ], 200);
            }

            $post = $request->user()->posts()->create($data);
            DB::commit();

            return ApiResponse::success($post, 'Post created successfully', 201);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => true,
                'data' => '',
                'message' => env('APP_DEBUG') ? $e->getMessage() : 'Faild to save post',
            ], 500);
        }
    }

    /**
     * Display a single post.
     */
    public function show($id)
    {
        DB::beginTransaction();
        try {
            $post = Post::with('user')->findOrFail($id);

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Post fetched successfully',
                'data' => $post
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => true,
                'message' => env('APP_DEBUG') ? $e->getMessage() : "Post with ID {$id} not found"
            ], 404);
        }
    }

    /**
     * Update a post (PUT).
     */
    public function update(Request $request, $id)
    {
        return $this->store($request, $id); // Reuse store logic
    }

    /**
     * Delete a post.
     */
    public function destroy($id)
    {
        try {
            $post = Post::findOrFail($id); 
            $post->delete();

                return response()->json([
                'success' => true,
                'message' => "Post with ID {$id} deleted successfully"
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => true,
                'message' => env('APP_DEBUG') ? $e->getMessage() : "Failed to delete post"
            ], 500);
        }
    }
}
