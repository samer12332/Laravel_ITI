@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-6xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="space-y-12">
            <section class="overflow-hidden rounded-md border border-slate-300 bg-white shadow-sm">
                <div class="border-b border-slate-300 bg-slate-100 px-5 py-4 text-lg text-slate-700">
                    Post Info
                </div>

                <div class="space-y-4 px-5 py-6 text-slate-800">
                    <p class="text-4xl font-bold leading-tight">
                        <span>Title :- </span>
                        <span class="font-normal">{{ ucfirst($innerPost['title']) }}</span>
                    </p>

                    <div class="space-y-1">
                        <p class="text-3xl font-bold">Description :-</p>
                        <p class="text-2xl text-slate-700">{{ $innerPost['description'] }}</p>
                    </div>
                </div>
            </section>

            <section class="overflow-hidden rounded-md border border-slate-300 bg-white shadow-sm">
                <div class="border-b border-slate-300 bg-slate-100 px-5 py-4 text-lg text-slate-700">
                    Post Creator Info
                </div>

                <div class="space-y-4 px-5 py-6 text-slate-800">
                    <p class="text-4xl leading-tight">
                        <span class="font-bold">Name :- </span>
                        <span>{{ $innerPost['creator']['name'] }}</span>
                    </p>

                    <p class="text-4xl leading-tight">
                        <span class="font-bold">Email :- </span>
                        <span>{{ $innerPost['creator']['email'] }}</span>
                    </p>

                    <p class="text-4xl leading-tight">
                        <span class="font-bold">Created At :- </span>
                        <span>{{ \Illuminate\Support\Carbon::parse($innerPost['creator']['created_at'])->format('l jS \o\f F Y h:i:s A') }}</span>
                    </p>
                </div>
            </section>
        </div>
    </div>
@endsection
