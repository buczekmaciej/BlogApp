@extends('base')

@section('title', 'Tags')

@section('body')
    <div class="px-24 py-10 w-3/4 flex flex-col items-center gap-8">
        <p class="text-4xl font-semibold">Our tags</p>
        <div class="flex flex-wrap justify-center gap-2">
            @foreach ($tags as $tag)
                <a class="bg-blue-800/5 text-blue-800 font-medium px-5 py-3 rounded-md"
                   href="{{ route('tags.view', $tag->name) }}">#{{ $tag->name }}</a>
            @endforeach
        </div>
    </div>
@endsection
