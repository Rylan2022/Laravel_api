<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function postCount() 
    {
        $users = User::withCount('posts')->get();

        return ApiResponse::success(
            $users,
            'Users with post counts fetched successfully'
        );
    }

    public function usersWithPosts()
{
    $users = User::with('posts')->get();
    return ApiResponse::success(
        $users,
        'Users with their posts fetched successfully'
    );
}

}
