@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="rounded-md bg-white p-8 shadow-sm ring-1 ring-slate-200">
            <form action="{{ route('posts.store') }}" method="POST" class="space-y-8">
                @csrf
                @include('layouts.post-form', [
                    'creators' => $creators,
                    'titleValue' => '',
                    'descriptionValue' => '',
                    'creatorValue' => $creators->first()?->id,
                ])

                <button
                    type="submit"
                    class="inline-flex rounded-md bg-emerald-500 px-5 py-3 text-lg font-medium text-white transition hover:bg-emerald-600"
                >
                    Create
                </button>
            </form>
        </div>
    </div>
@endsection
