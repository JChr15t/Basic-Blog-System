<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Http\Requests\CommentRequest;
use App\Http\Resources\CommentResource;
use Illuminate\Support\Facades\Gate;

class PostCommentController extends Controller
{

    public function index(Post $post)
    {
        $comments = $post->comments()->get();
        return CommentResource::collection($comments);
    }


    public function store(Post $post, CommentRequest $request)
    {
        $comment = new Comment([
            'user_id' => $request->user()->id,
            'body' => $request->input('body'),
        ]);

        $post->comments()->save($comment);

        return new CommentResource($comment);
    }


    public function show(Post $post, Comment $comment)
    {
        if ($comment->post_id !== $post->id) {
            return response()->json(['message' => 'Comment not found for this post'], 404);
        }

        return new CommentResource($comment);
    }


    public function update(CommentRequest $request, Post $post, Comment $comment)
    {
        if ($comment->post_id !== $post->id) {
            return response()->json(['message' => 'Comment not found for this post'], 404);
        }

        $comment->update($request->validated());

        return new CommentResource($comment);
    }


    public function destroy(Post $post, Comment $comment)
    {
        if (Gate::denies('delete', $comment)) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        if ($comment->post_id !== $post->id) {
            return response()->json(['message' => 'Comment not found for this post'], 404);
        }

        $comment->delete();

        return response()->noContent();
    }
}
