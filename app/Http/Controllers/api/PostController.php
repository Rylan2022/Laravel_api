<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Helpers\ApiResponse;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
// use Illuminate\Database\Eloquent\ModelNotFoundException;
// use Illuminate\Validation\ValidationException;
use Exception;
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

            $query = Post::query();

            if ($request->has('search')) {
                $search = $request->input('search');
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('content', 'like', "%{$search}%");
                });
            }

            $totalEntries = $query->count();

            if ($totalEntries === 0) {
                return ApiResponse::error('No posts found', 404, []);
            }

            $posts = $query->orderBy($orderBy, $orderbyDesc)
                ->skip($offset)
                ->take($limit)
                ->get();

            $currentPage = intval(($offset / $limit) + 1);
            $totalPages  = (int) ceil($totalEntries / $limit);
            $nextPage    = $currentPage < $totalPages ? $currentPage + 1 : null;


            return ApiResponse::success(
                $posts,
                'Posts fetched successfully',
                200,
                [
                    'total_entries' => $totalEntries,
                    'total_pages'   => $totalPages,
                    'current_page'  => $currentPage,
                    'limit'         => $limit,
                    'next_page'     => $nextPage,
                ]
            );
        } catch (Exception $e) {
            return ApiResponse::error(
                'Failed to fetch posts',
                500,
                env('APP_DEBUG') ? $e->getMessage() : []
            );
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
            ];

            if ($id) {
                $post = Post::findOrFail($id);
                $post->update($data);
                DB::commit();
                return ApiResponse::success($post, 'Post updated successfully', 200);
            }

            $post = Post::create($data);
            DB::commit();

            return ApiResponse::success($post, 'Post created successfully', 201);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => true,
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
            $post = Post::findOrFail($id);

            DB::commit();
            return ApiResponse::success($post, 'Post fetched successfully');
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
    // public function update(Request $request, $id)
    // {
    //     return $this->store($request, $id); // Reuse store logic
    // }

    /**
     * Delete a post.
     */
    public function destroy($id)
    {
        try {
            $post = Post::findOrFail($id);
            $post->delete();

            return ApiResponse::success([], "Post with ID {$id} deleted successfully");
        } catch (Exception $e) {
            return response()->json([
                'error' => true,
                'message' => env('APP_DEBUG') ? $e->getMessage() : "Failed to delete post"
            ], 500);
        }
    }
}
