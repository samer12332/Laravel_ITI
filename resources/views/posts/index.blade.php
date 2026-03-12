@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-6xl px-4 py-12 sm:px-6 lg:px-8">
        @if (session('success'))
            <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead class="bg-slate-100 text-slate-700">
                        <tr class="border-b border-slate-200">
                            <th class="px-6 py-4 font-semibold">#</th>
                            <th class="px-6 py-4 font-semibold">Title</th>
                            <th class="px-6 py-4 font-semibold">Posted By</th>
                            <th class="px-6 py-4 font-semibold">Created At</th>
                            <th class="px-6 py-4 font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @foreach ($posts as $post)
                            <tr class="bg-white">
                                <td class="px-6 py-5">{{ $post->id }}</td>
                                <td class="px-6 py-5">{{ ucfirst($post->title) }}</td>
                                <td class="px-6 py-5">{{ $post->user->name ?? 'Unknown' }}</td>
                                <td class="px-6 py-5">
                                    {{ $post->created_at?->toDateString() }}</td>
                                <td class="px-6 py-5">
                                    <div class="flex flex-wrap gap-2">
                                        <x-button type="primary" :href="route('posts.show', $post->id)">
                                            View
                                        </x-button>
                                        <x-button type="secondary" :href="route('posts.edit', $post->id)">
                                            Edit
                                        </x-button>
                                        <form
                                            action="{{ route('posts.destroy', $post->id) }}"
                                            method="POST"
                                            class="inline"
                                            onsubmit="return confirm('Are you sure you want to delete this post?');"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <x-button type="danger" button-type="submit">
                                                Delete
                                            </x-button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-200 bg-slate-50 px-6 py-4">
                {{ $posts->links() }}
            </div>
        </div>
    </div>
@endsection
