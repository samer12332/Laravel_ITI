@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-6xl px-4 py-12 sm:px-6 lg:px-8">
        @if (session('success'))
            <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="space-y-12">
            <section class="overflow-hidden rounded-md border border-slate-300 bg-white shadow-sm">
                <div class="border-b border-slate-300 bg-slate-100 px-5 py-4 text-lg text-slate-700">
                    Post Info
                </div>

                <div class="space-y-4 px-5 py-6 text-slate-800">
                    <p class="text-4xl font-bold leading-tight">
                        <span>Title :- </span>
                        <span class="font-normal">{{ ucfirst($post->title) }}</span>
                    </p>

                    <div class="space-y-1">
                        <p class="text-3xl font-bold">Description :-</p>
                        <p class="text-2xl text-slate-700">{{ $post->description }}</p>
                    </div>

                    <p class="text-2xl leading-tight">
                        <span class="font-bold">Created At :- </span>
                        <span>{{ $post->created_at?->format('l jS \o\f F Y h:i:s A') }}</span>
                    </p>
                </div>
            </section>

            <section class="overflow-hidden rounded-md border border-slate-300 bg-white shadow-sm">
                <div class="border-b border-slate-300 bg-slate-100 px-5 py-4 text-lg text-slate-700">
                    Post Creator Info
                </div>

                <div class="space-y-4 px-5 py-6 text-slate-800">
                    <p class="text-4xl leading-tight">
                        <span class="font-bold">Name :- </span>
                        <span>{{ $post->user->name ?? 'Unknown' }}</span>
                    </p>

                    <p class="text-4xl leading-tight">
                        <span class="font-bold">Email :- </span>
                        <span>{{ $post->user->email ?? 'Unknown' }}</span>
                    </p>

                    <p class="text-4xl leading-tight">
                        <span class="font-bold">Created At :- </span>
                        <span>{{ $post->user?->created_at?->format('l jS \o\f F Y h:i:s A') ?? 'Unknown' }}</span>
                    </p>
                </div>
            </section>

            <section class="overflow-hidden rounded-md border border-slate-300 bg-white shadow-sm">
                <div class="border-b border-slate-300 bg-slate-100 px-5 py-4 text-lg text-slate-700">
                    Comments
                </div>

                <div class="space-y-8 px-5 py-6">
                    <form action="{{ route('comments.store', $post->id) }}" method="POST" class="space-y-4">
                        @csrf

                        <div class="space-y-2">
                            <label for="author_name" class="block text-sm font-medium text-slate-700">Your Name</label>
                            <input
                                id="author_name"
                                name="author_name"
                                type="text"
                                value="{{ old('author_name') }}"
                                class="block w-full rounded-md border border-slate-300 px-4 py-3 text-base text-slate-800 outline-none transition focus:border-sky-500 focus:ring-4 focus:ring-sky-100"
                            >
                            @error('author_name')
                                <p class="text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="body" class="block text-sm font-medium text-slate-700">Comment</label>
                            <textarea
                                id="body"
                                name="body"
                                rows="4"
                                class="block w-full rounded-md border border-slate-300 px-4 py-3 text-base text-slate-800 outline-none transition focus:border-sky-500 focus:ring-4 focus:ring-sky-100"
                            >{{ old('body') }}</textarea>
                            @error('body')
                                <p class="text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <x-button type="primary" button-type="submit">
                            Add Comment
                        </x-button>
                    </form>

                    <div class="space-y-4">
                        @forelse ($post->comments as $comment)
                            <article class="rounded-lg border border-slate-200 bg-slate-50 p-5">
                                @if ($editingComment?->id === $comment->id)
                                    <form action="{{ route('comments.update', [$post->id, $comment->id]) }}" method="POST" class="space-y-4">
                                        @csrf
                                        @method('PUT')

                                        <div class="space-y-2">
                                            <label for="author_name_{{ $comment->id }}" class="block text-sm font-medium text-slate-700">Your Name</label>
                                            <input
                                                id="author_name_{{ $comment->id }}"
                                                name="author_name"
                                                type="text"
                                                value="{{ old('author_name', $comment->author_name) }}"
                                                class="block w-full rounded-md border border-slate-300 px-4 py-3 text-base text-slate-800 outline-none transition focus:border-sky-500 focus:ring-4 focus:ring-sky-100"
                                            >
                                        </div>

                                        <div class="space-y-2">
                                            <label for="body_{{ $comment->id }}" class="block text-sm font-medium text-slate-700">Comment</label>
                                            <textarea
                                                id="body_{{ $comment->id }}"
                                                name="body"
                                                rows="4"
                                                class="block w-full rounded-md border border-slate-300 px-4 py-3 text-base text-slate-800 outline-none transition focus:border-sky-500 focus:ring-4 focus:ring-sky-100"
                                            >{{ old('body', $comment->body) }}</textarea>
                                        </div>

                                        <div class="flex flex-wrap gap-2">
                                            <x-button type="secondary" button-type="submit">
                                                Save Comment
                                            </x-button>
                                            <x-button type="primary" :href="route('posts.show', $post->id)">
                                                Cancel
                                            </x-button>
                                        </div>
                                    </form>
                                @else
                                    <div class="flex flex-wrap items-start justify-between gap-4 sm:flex-nowrap">
                                        <div class="space-y-2">
                                            <p class="text-lg font-semibold text-slate-800">{{ $comment->author_name }}</p>
                                            <p class="text-sm text-slate-500">{{ $comment->created_at?->toDateString() }}</p>
                                            <p class="text-base text-slate-700">{{ $comment->body }}</p>
                                        </div>

                                        <div class="flex shrink-0 flex-wrap items-center gap-2">
                                            <x-button
                                                type="secondary"
                                                :href="route('posts.show', ['id' => $post->id, 'editing_comment' => $comment->id])"
                                            >
                                                Edit
                                            </x-button>

                                            <form
                                                action="{{ route('comments.destroy', [$post->id, $comment->id]) }}"
                                                method="POST"
                                                class="inline-flex"
                                                onsubmit="return confirm('Are you sure you want to delete this comment?');"
                                            >
                                                @csrf
                                                @method('DELETE')
                                                <x-button type="danger" button-type="submit">
                                                    Delete
                                                </x-button>
                                            </form>
                                        </div>
                                    </div>
                                @endif
                            </article>
                        @empty
                            <p class="text-base text-slate-500">No comments yet. Be the first to add one.</p>
                        @endforelse
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
