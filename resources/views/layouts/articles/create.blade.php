@extends('base')

@section('title', 'Create article')

@section('body')
    <div class="w-1/2 py-10 px-8 my-10 bg-white text-natural-900 flex flex-col items-center gap-3 rounded-md shadow-md">
        <p class="font-bold text-4xl">New article</p>
        <form class="flex flex-col gap-4 w-full"
              id="article-create"
              method="post">
            @include('components.form-box', [
                'id' => 'title',
                'label' => 'Title',
                'name' => 'title',
                'error' => $errors->has('title') ? $errors->first('title') : null,
                'extra' => "<span class='input-help'>Title will be used as identifier</span>",
                'value' => session()->get('article.title'),
            ])
            @include('components.form-box', [
                'item' => 'textarea',
                'id' => 'content',
                'label' => 'Content',
                'name' => 'content',
                'error' => $errors->has('content') ? $errors->first('content') : null,
                'extra' => "<span class='input-help'>You will be able to add images in further steps</span>",
                'value' => session()->get('article.content'),
            ])
            <div class="tags-container flex flex-col gap-2">
                <label class="text-lg">Tags</label>
                <div class="w-full flex items-center gap-2 relative outline-transparent"
                     tabindex="0">
                    <input autocomplete="off"
                           class="form-input w-1/3"
                           id="tags">
                    <div class="list flex flex-wrap gap-2 w-2/3"></div>
                    <div class="results absolute left-0 bottom-full z-10 bg-slate-100 rounded-md h-0 max-h-[50vh] w-1/3 overflow-auto">
                        @foreach ($tags as $tag)
                            <p class="tag-suggestion">{{ $tag }}</p>
                        @endforeach
                    </div>
                </div>
                <span class='input-help'>First tag will be representing tag, so pick it wise. At least 1 tag required</span>
                @error('tags')
                    <p class="font-medium text-red-500">{{ $errors->first('tags') }}</p>
                @enderror
            </div>

            <button class="form-btn">Upload images</button>
            @csrf
        </form>
    </div>
@endsection

@section('javascripts')
    <script src="{{ asset('js/create.js') }}"></script>
@endsection
