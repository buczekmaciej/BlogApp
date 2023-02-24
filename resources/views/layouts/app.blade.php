@extends('base')

@section('title', 'Homepage')

@section('body')
    @include('components.title-article', ['article' => $articles['latest']])
    <div class="w-full flex gap-4 px-24 py-10">
        <div class="w-3/4 grid grid-cols-2 gap-4">
            @foreach ($articles['articles'] as $article)
                @include('components.article', ['article' => $article])
            @endforeach
            <a class="col-span-2 w-full bg-blue-600 text-slate-50 py-4 rounded-md text-center"
               href="{{ route('articles.list') }}">Read more</a>
        </div>
        <div class="w-1/4">a</div>
    </div>
@endsection
