<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    private function validateComment(Request $request): array
    {
        return $request->validate([
            'author_name' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
        ]);
    }

    public function store(Request $request, int $postId): RedirectResponse
    {
        $post = Post::query()->find($postId);
        abort_if(!$post, 404);

        $validated = $this->validateComment($request);

        $post->comments()->create($validated);

        return redirect()
            ->route('posts.show', $postId)
            ->with('success', 'Comment added successfully.');
    }

    public function update(Request $request, int $postId, int $commentId): RedirectResponse
    {
        $post = Post::query()->find($postId);
        abort_if(!$post, 404);

        $comment = Comment::query()
            ->where('commentable_type', Post::class)
            ->where('commentable_id', $post->id)
            ->find($commentId);
        abort_if(!$comment, 404);

        $validated = $this->validateComment($request);

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
