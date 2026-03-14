<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;

class CommentController extends Controller
{
    public function store(CommentRequest $request, int $postId): RedirectResponse
    {
        $post = Post::query()->find($postId);
        abort_if(!$post, 404);

        $validated = $request->validated();

        $post->comments()->create($validated);

        return redirect()
            ->route('posts.show', $postId)
            ->with('success', 'Comment added successfully.');
    }

    public function update(CommentRequest $request, int $postId, int $commentId): RedirectResponse
    {
        $post = Post::query()->find($postId);
        abort_if(!$post, 404);

        $comment = Comment::query()
            ->where('commentable_type', Post::class)
            ->where('commentable_id', $post->id)
            ->find($commentId);
        abort_if(!$comment, 404);

        $validated = $request->validated();

        $comment->update($validated);

        return redirect()
            ->route('posts.show', $postId)
            ->with('success', 'Comment updated successfully.');
    }

    public function destroy(int $postId, int $commentId): RedirectResponse
    {
        $post = Post::query()->find($postId);
        abort_if(!$post, 404);

        $comment = Comment::query()
            ->where('commentable_type', Post::class)
            ->where('commentable_id', $post->id)
            ->find($commentId);
        abort_if(!$comment, 404);

        $comment->delete();

        return redirect()
            ->route('posts.show', $postId)
            ->with('success', 'Comment deleted successfully.');
    }
}
