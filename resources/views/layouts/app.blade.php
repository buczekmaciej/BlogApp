@extends('base')

@section('title', 'Homepage')

@section('body')
    @include('components.title-article', ['article' => $articles['latest']])
    <div class="w-full flex gap-4 px-24 py-10">
        <div class="w-3/4 grid grid-cols-2 gap-4">
            <p class="col-span-2 font-semibold text-2xl">Recent articles</p>
            @foreach ($articles['articles'] as $article)
                @include('components.article', ['article' => $article])
            @endforeach
            <a class="col-span-2 w-full bg-blue-600 text-slate-50 py-3 rounded-md text-center"
               href="{{ route('articles.list') }}">Read more</a>
        </div>
        <div class="px-4 w-1/4 flex flex-col gap-8">
            <div class="flex flex-col gap-4">
                <p class="text-2xl font-semibold">Recently active tags</p>
                <div class="flex flex-col px-4 items-start gap-4">
                    @foreach ($tags['recent'] as $tag => $count)
                        <a class="text-blue-600"
                           href="{{ route('tags.view', $tag) }}">#{{ $tag }} ({{ $count }})</a>
                    @endforeach
                </div>
            </div>
            <div class="flex flex-col gap-4">
                <p class="text-2xl font-semibold">Most active tags</p>
                <div class="flex flex-col px-4 items-start gap-4">
                    @foreach ($tags['active'] as $tag)
                        <a class="text-blue-600"
                           href="{{ route('tags.view', $tag->name) }}">#{{ $tag->name }} ({{ $tag->articles()->count() }})</a>
                    @endforeach
                </div>
            </div>
            <a class="w-full bg-blue-600 text-slate-50 py-3 rounded-md text-center"
               href="{{ route('tags.list') }}">Show all tags</a>
        </div>
    </div>
@endsection
