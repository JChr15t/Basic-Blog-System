<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{

    public function index()
    {
        $posts = Post::all();
        return PostResource::collection($posts);
    }


    public function store(PostRequest $request)
    {
        $post = Post::create($request->validated());
        return new PostResource($post);
    }


    public function show(Post $post)
    {
        return new PostResource($post);
    }


    public function update(PostRequest $request, Post $post)
    {
        $post->update($request->validated());
        return new PostResource($post);
    }

    
    public function destroy(Post $post)
    {
        if (Gate::denies('delete', $post)) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $post->delete();
        return response()->json(['message' => 'Post deleted successfully'], 200);
    }
}
