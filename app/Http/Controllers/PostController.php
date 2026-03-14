<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PostController extends Controller
{
    private function creators()
    {
        return User::query()
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    private function postListItem(Post $post): array
    {
        return [
            'id' => $post->id,
            'title' => $post->title,
            'slug' => $post->slug,
            'image_url' => $post->image_url,
            'created_at' => $post->created_at?->toDateString(),
            'user' => [
                'name' => $post->user->name ?? 'Unknown',
            ],
        ];
    }

    private function postDetails(Post $post): array
    {
        return [
            'id' => $post->id,
            'title' => $post->title,
            'slug' => $post->slug,
            'description' => $post->description,
            'image_url' => $post->image_url,
            'created_at' => $post->created_at?->format('l jS \o\f F Y h:i:s A'),
            'user_id' => $post->user_id,
            'user' => [
                'name' => $post->user->name ?? 'Unknown',
                'email' => $post->user->email ?? 'Unknown',
                'created_at' => $post->user?->created_at?->format('l jS \o\f F Y h:i:s A') ?? 'Unknown',
            ],
            'comments' => $post->comments->map(fn ($comment) => [
                'id' => $comment->id,
                'author_name' => $comment->author_name,
                'body' => $comment->body,
                'created_at' => $comment->created_at?->toDateString(),
            ])->values(),
        ];
    }

    public function index(Request $request): Response
    {
        $posts = Post::query()
            ->with('user')
            ->latest()
            ->paginate(5)
            ->through(fn (Post $post) => $this->postListItem($post));

        return Inertia::render('Posts/Index', [
            'posts' => $posts,
        ]);
    }


    public function show(Request $request, int $id): Response
    {
        $post = Post::query()
            ->with([
                'user',
                'comments' => fn ($query) => $query->latest(),
            ])
            ->find($id);
        abort_if(!$post, 404);

        $editingComment = null;

        if ($request->filled('editing_comment')) {
            $editingComment = $post->comments->firstWhere('id', (int) $request->integer('editing_comment'));
        }

        return Inertia::render('Posts/Show', [
            'post' => $this->postDetails($post),
            'editingCommentId' => $editingComment?->id,
        ]);
    }

    public function create(): Response
    {
        $creators = $this->creators();

        return Inertia::render('Posts/Create', [
            'creators' => $creators,
        ]);
    }

    public function store(PostRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        Post::query()->create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'image' => $validated['image'],
            'user_id' => $validated['creator'],
        ]);


        return redirect()
            ->route('posts.index')
            ->with('success', 'Post created successfully.');
    }

    public function edit(Request $request, int $id): Response
    {
        $post = Post::query()
            ->with('user')
            ->find($id);
        abort_if(!$post, 404);

        $creators = $this->creators();

        return Inertia::render('Posts/Edit', [
            'post' => $this->postDetails($post),
            'creators' => $creators,
        ]);
    }

    public function update(PostRequest $request, int $id): RedirectResponse
    {
        $validated = $request->validated();

        $post = Post::find($id);
        abort_if(!$post, 404);
        $attributes = [
            'title' => $validated['title'],
            'description' => $validated['description'],
            'user_id' => $validated['creator'],
        ];

        if (array_key_exists('image', $validated)) {
            $attributes['image'] = $validated['image'];
        }

        $post->update($attributes);

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
