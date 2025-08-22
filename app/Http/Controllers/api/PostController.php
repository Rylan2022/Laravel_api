<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Helpers\ApiResponse;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Exception;

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
            $orderBy  = $request->get('order_by', 'created_at');

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
                return response()->json([
                    'success' => false,
                    'message' => 'No posts found',
                    'data'    => []
                ], 404);
            }

            $posts = $query->orderBy($orderBy)
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
        try {
            $rules = [
                'title'   => ['required', Rule::unique('posts', 'title')->ignore($id)],
                'content' => 'required',
            ];

            $messages = [
                'title.required'   => 'A title is required.',
                'title.unique'     => 'This title already exists.',
                'content.required' => 'Content cannot be empty.',
            ];

            $request->validate($rules, $messages);

            $data = [
                'title'   => $request->input('title'),
                'content' => $request->input('content'),
            ];

            if ($id) {
                $post = Post::findOrFail($id);
                $post->update($data);


                return ApiResponse::success($post, 'Post updated successfully', 200);
            }

            $post = Post::create($data);

            return ApiResponse::success($post, 'Post created successfully', 201);
        } catch (ValidationException $e) {
            return ApiResponse::error('Validation failed', 422, $e->errors());
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error('Post not found for update', 404);
        } catch (Exception $e) {
            return ApiResponse::error(
                'Failed to save post',
                500,
                env('APP_DEBUG') ? $e->getMessage() : []
            );
        }
    }

    /**
     * Display a single post.
     */
    public function show($id)
    {
        try {
            $post = Post::findOrFail($id);

            return ApiResponse::success($post, 'Post fetched successfully');
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error("Post with ID {$id} not found", 404);
        } catch (Exception $e) {
            return ApiResponse::error(
                'Failed to fetch post',
                500,
                env('APP_DEBUG') ? $e->getMessage() : []
            );
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

            return ApiResponse::success([], "Post with ID {$id} deleted successfully");
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error("Post with ID {$id} not found", 404);
        } catch (Exception $e) {
            return ApiResponse::error(
                'Failed to delete post',
                500,
                env('APP_DEBUG') ? $e->getMessage() : []
            );
        }
    }
}
