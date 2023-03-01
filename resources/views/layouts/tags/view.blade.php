@extends('base')

@section('title', "#{$tag->name}")

@section('body')
    <div class="w-1/2 py-16 flex flex-col gap-6">
        <div class="flex gap-2 items-center text-2xl">
            <a class="underline text-blue-800 font-semibold"
               href="{{ route('tags.list') }}">Tags</a>
            <svg class="h-6 fill-blue-800"
                 viewBox="0 0 256 512"
                 xmlns="http://www.w3.org/2000/svg">
                <path d="M224.3 273l-136 136c-9.4 9.4-24.6 9.4-33.9 0l-22.6-22.6c-9.4-9.4-9.4-24.6 0-33.9l96.4-96.4-96.4-96.4c-9.4-9.4-9.4-24.6 0-33.9L54.3 103c9.4-9.4 24.6-9.4 33.9 0l136 136c9.5 9.4 9.5 24.6.1 34z"></path>
            </svg>
            <p class="">{{ $tag->name }}</p>
        </div>
        <div class="flex flex-col gap-6">
            @foreach ($articles as $article)
                @include('components.article', ['article' => $article, 'format' => 'F d, Y | H:i:s'])
            @endforeach
            {{ $articles->links() }}
        </div>
    </div>
@endsection
