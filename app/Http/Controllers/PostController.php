<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostController extends Controller
{
    private function defaultPosts(): array
    {
        return [
            [
                'id' => 1,
                'title' => 'special title treatment',
                'description' => 'With supporting text below as a natural lead-in to additional content.',
                'created_at' => '2026-03-11 10:00:00',
                'creator' => [
                    'name' => 'Ahmed',
                    'email' => 'ahmed@gmail.com',
                    'created_at' => '1975-12-25 14:15:16',
                ],
            ],
            [
                'id' => 2,
                'title' => 'solid principles',
                'description' => 'A concise overview of the core SOLID design principles.',
                'created_at' => '2026-03-11 10:00:00',
                'creator' => [
                    'name' => 'Mohamed',
                    'email' => 'mohamed@gmail.com',
                    'created_at' => '2024-09-01 08:00:00',
                ],
            ],
            [
                'id' => 3,
                'title' => 'design patterns',
                'description' => 'A quick reference to reusable solutions for common software problems.',
                'created_at' => '2026-03-11 10:00:00',
                'creator' => [
                    'name' => 'Ali',
                    'email' => 'ali@gmail.com',
                    'created_at' => '2024-09-01 08:00:00',
                ],
            ],
        ];
    }

    private function creators(): array
    {
        return [
            'Ahmed' => [
                'name' => 'Ahmed',
                'email' => 'ahmed@gmail.com',
                'created_at' => '1975-12-25 14:15:16',
            ],
            'Mohamed' => [
                'name' => 'Mohamed',
                'email' => 'mohamed@gmail.com',
                'created_at' => '2024-09-01 08:00:00',
            ],
            'Ali' => [
                'name' => 'Ali',
                'email' => 'ali@gmail.com',
                'created_at' => '2024-09-01 08:00:00',
            ],
        ];
    }

    private function posts(Request $request): array
    {
        return $request->session()->get('posts', $this->defaultPosts());
    }

    private function validatePost(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'creator' => ['required', 'in:' . implode(',', array_keys($this->creators()))],
        ]);
    }

    public function index(Request $request): View
    {
        $posts = $this->posts($request);

        return view('posts.index', compact('posts'));
    }


    public function show(Request $request, int $id): View
    {
        $innerPost = collect($this->posts($request))->firstWhere('id', $id);

        abort_if(!$innerPost, 404);

        return view('posts.show', compact('innerPost'));
    }

    public function create(): View
    {
        $creators = array_keys($this->creators());

        return view('posts.create', compact('creators'));
    }

    public function store(Request $request): RedirectResponse
    {
        $creators = $this->creators();
        $validated = $this->validatePost($request);

        $posts = $this->posts($request);
        $nextId = collect($posts)->max('id') + 1;

        $posts[] = [
            'id' => $nextId,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'created_at' => now()->toDateTimeString(),
            'creator' => $creators[$validated['creator']],
        ];

        $request->session()->put('posts', $posts);

        return redirect()
            ->route('posts.index')
            ->with('success', 'Post created successfully.');
    }

    public function edit(Request $request, int $id): View
    {
        $post = collect($this->posts($request))->firstWhere('id', $id);
        abort_if(!$post, 404);

        $creators = array_keys($this->creators());

        return view('posts.edit', compact('post', 'creators'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $posts = $this->posts($request);
        $postIndex = collect($posts)->search(fn(array $post) => $post['id'] === $id);

        abort_if($postIndex === false, 404);

        $validated = $this->validatePost($request);
        $creator = $this->creators()[$validated['creator']];

        $posts[$postIndex]['title'] = $validated['title'];
        $posts[$postIndex]['description'] = $validated['description'];
        $posts[$postIndex]['creator'] = $creator;

        $request->session()->put('posts', $posts);

        return redirect()
            ->route('posts.index')
            ->with('success', 'Post updated successfully.');
    }

    public function destroy(Request $request, int $id): RedirectResponse
    {
        $posts = $this->posts($request);
        $postIndex = collect($posts)->search(fn(array $post) => $post['id'] === $id);

        abort_if($postIndex === false, 404);

        array_splice($posts, $postIndex, 1);

        $request->session()->put('posts', $posts);

        return redirect()
            ->route('posts.index')
            ->with('success', 'Post deleted successfully.');
    }

}
