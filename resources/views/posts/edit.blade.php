@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="rounded-md bg-white p-8 shadow-sm ring-1 ring-slate-200">
            <form action="{{ route('posts.update', $post->id) }}" method="POST" class="space-y-8">
                @csrf
                @method('PUT')
                @include('layouts.post-form', [
                    'creators' => $creators,
                    'titleValue' => ucfirst($post->title),
                    'descriptionValue' => $post->description,
                    'creatorValue' => $post->user_id,
                ])

                <button
                    type="submit"
                    class="inline-flex rounded-md bg-blue-500 px-5 py-3 text-lg font-medium text-white transition hover:bg-blue-600"
                >
                    Update
                </button>
            </form>
        </div>
    </div>
@endsection
