<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Console\View\Components\Task;
use Illuminate\Http\Request;

class ProController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) {
        
        $limit = (int) $request->get('limit', 10);
        $offset = (int) $request->get('offset', 0);
        $orderBy = $request->get('order_by', 'created_at');
        $orderdir = $request->get('order_dir', 'desc');

        $query = Post::query();

        if($request->has('search')) {
            $query->where(function ($q) use ($request){
                $q->where('title', 'like', "%{$request->search}%")
                ->orWhere('content', 'like', "%{$request}%");
            });
        }

        $totalEntries = $query->count();

        $posts = $query->orderBy($orderBy, $orderBy)
        ->skip($offset)
        ->take($limit)
        ->get();

        $currentPage = intval(($offset / $limit) + 1);
        $totalPages = (int) ceil($totalEntries / $limit);
        $nextPage = $




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
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
