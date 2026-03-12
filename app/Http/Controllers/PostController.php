<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostController extends Controller
{
    private function creators()
    {
        return User::query()
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    private function validatePost(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'creator' => ['required', 'exists:users,id'],
        ]);
    }

    public function index(Request $request): View
    {
        $posts = Post::query()
            ->with('user')
            ->latest()
            ->get();

        return view('posts.index', compact('posts'));
    }


    public function show(Request $request, int $id): View
    {
        $post = Post::query()
            ->with('user')
            ->find($id);
        abort_if(!$post, 404);

        return view('posts.show', compact('post'));
    }

    public function create(): View
    {
        $creators = $this->creators();

        return view('posts.create', compact('creators'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatePost($request);

        Post::query()->create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'user_id' => $validated['creator'],
        ]);


        return redirect()
            ->route('posts.index')
            ->with('success', 'Post created successfully.');
    }

    public function edit(Request $request, int $id): View
    {
        $post = Post::query()
            ->with('user')
            ->find($id);
        abort_if(!$post, 404);

        $creators = $this->creators();

        return view('posts.edit', compact('post', 'creators'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $validated = $this->validatePost($request);

        $post = Post::find($id);
        abort_if(!$post, 404);
        $post->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'user_id' => $validated['creator'],
        ]);

        return redirect()
            ->route('posts.index')
            ->with('success', 'Post updated successfully.');
    }

    public function destroy(Request $request, int $id): RedirectResponse
    {
        $post = Post::find($id);
        abort_if(!$post, 404);
        $post->delete();

        return redirect()
            ->route('posts.index')
            ->with('success', 'Post deleted successfully.');
    }

}
