@extends('base')

@section('title', 'Upload images')

@section('body')
    <div class="w-1/2 py-10 px-8 my-10 bg-white text-natural-900 flex flex-col items-center gap-3 rounded-md shadow-md">
        <p class="font-bold text-4xl">Upload images</p>
        <form class="flex flex-col gap-4 w-full"
              method="post">
            <input accept="image/*"
                   id=""
                   multiple
                   name="files"
                   type="file">
            <div class="flex justify-between">
                <a class="form-btn"
                   href="{{ route('articles.create') }}">Back to content</a>
                <button class="form-btn">Build layout</button>
            </div>
            @csrf
        </form>
    </div>
@endsection
