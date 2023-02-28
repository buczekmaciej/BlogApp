@extends('base')

@section('title', 'Create article')

@section('body')
    <div class="w-1/2 py-10 px-8 my-10 bg-white text-natural-900 flex flex-col items-center gap-3 rounded-md shadow-md">
        <p class="font-bold text-4xl">Build layout</p>
        <form class="flex flex-col gap-4 w-full"
              method="post">
            <div class="flex justify-between">
                <a class="form-btn self-start"
                   href="{{ route('articles.createImages') }}">Back to images</a>
                <button class="form-btn">Create article</button>
            </div>
            @csrf
        </form>
    </div>
@endsection
