@extends('base')

@section('title', "Update \"{$article->title}\"")

@section('body')
    <div class="w-4/5 py-10 px-8 my-10 bg-white text-natural-900 flex flex-col items-center gap-8 rounded-md shadow-md">
        <p class="font-bold text-4xl">Build layout</p>
        <form class="flex flex-col gap-3 w-full"
              id="article-create"
              method="post">
            @include('components.form-box', [
                'id' => 'title',
                'label' => 'Title',
                'name' => 'title',
                'error' => $errors->has('title') ? $errors->first('title') : null,
                'extra' => "<span class='input-help'>Title will be used as identifier</span>",
                'value' => $article->title,
            ])
            <div class="flex gap-3">
                <div class="w-2/3 flex flex-col gap-3">
                    <div class="flex justify-end items-center gap-3">
                        <button class="bold-text styling-btn font-bold"
                                type='button'>Bold</button>
                        <button class="italic-text styling-btn italic"
                                type='button'>Italic</button>
                        <button class="link-text styling-btn"
                                type='button'>Link</button>
                        <button class="place-img styling-btn"
                                type='button'>Image</button>
                    </div>
                    <div class=""></div>
                    @include('components.form-box', [
                        'item' => 'textarea',
                        'id' => 'content',
                        'label' => 'Content',
                        'name' => 'content',
                        'error' => $errors->has('content') ? $errors->first('content') : null,
                        'extra' => "<span class='input-help'>To replace images, just change image name (skip image used as thumbnail, it'll be added automatically)</span>",
                        'value' => $article->content,
                    ])
                </div>
                <div class="flex flex-col w-1/3 gap-3 h-[45.5rem] overflow-auto px-1">
                    @foreach (session()->get('existing') as $key => $img)
                        <div class="flex flex-col items-start gap-2">
                            <p class="text-sm">{{ $img }}</p>
                            <img alt=""
                                 class=""
                                 src="{{ asset('assets/images/' . $article->getStrippedUuid() . '/' . $img) }}">
                            <div class="flex items-center gap-2 cursor-pointer [&>*]:cursor-pointer">
                                <input @if ($article->thumbnail && $article->thumbnail === $img) checked @endif
                                       id="image-{{ $key }}"
                                       name="thumbnail"
                                       required
                                       type="radio"
                                       value="{{ $img }}" />
                                <label for="image-{{ $key }}">Use as thumbnail</label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="tags-container flex flex-col gap-2">
                <label class="text-lg">Tags <sup class="text-red-600">*</sup></label>
                <div class="w-full flex items-center gap-2 relative outline-transparent"
                     tabindex="0">
                    <input autocomplete="off"
                           class="form-input w-1/3"
                           id="tags">
                    <div class="list flex flex-wrap gap-2 w-2/3 [&>*]:tag-listed">
                        @foreach ($article->tags()->pluck('name') as $tag)
                            <p data-name="{{ $tag }}">
                                <svg class="remove-tag h-4 cursor-pointer"
                                     viewBox="0 0 512 512"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path
                                          d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm0 448c-110.5 0-200-89.5-200-200S145.5 56 256 56s200 89.5 200 200-89.5 200-200 200zm101.8-262.2L295.6 256l62.2 62.2c4.7 4.7 4.7 12.3 0 17l-22.6 22.6c-4.7 4.7-12.3 4.7-17 0L256 295.6l-62.2 62.2c-4.7 4.7-12.3 4.7-17 0l-22.6-22.6c-4.7-4.7-4.7-12.3 0-17l62.2-62.2-62.2-62.2c-4.7-4.7-4.7-12.3 0-17l22.6-22.6c4.7-4.7 12.3-4.7 17 0l62.2 62.2 62.2-62.2c4.7-4.7 12.3-4.7 17 0l22.6 22.6c4.7 4.7 4.7 12.3 0 17z">
                                    </path>
                                </svg>
                                <span>{{ $tag }}</span>
                            </p>
                        @endforeach
                    </div>
                    <div class="results absolute left-0 bottom-full z-10 bg-slate-100 rounded-md h-0 max-h-[50vh] w-1/3 overflow-auto">
                        @foreach ($unusedTags as $tag)
                            <p class="tag-suggestion">{{ $tag }}</p>
                        @endforeach
                    </div>
                </div>
                <span class='input-help'>First tag will be representing tag, so pick it wise. At least 1 tag required</span>
                @error('tags')
                    <p class="font-medium text-red-500">{{ $errors->first('tags') }}</p>
                @enderror
            </div>
            <div class="flex justify-between">
                <a class="form-btn self-start"
                   href="{{ route('articles.edit', ['article' => $article->slug]) }}">Back to images</a>
                <button class="form-btn">Update article</button>
            </div>
            @csrf
            @foreach ($article->tags()->pluck('name') as $tag)
                <input name="tags[]"
                       type="hidden"
                       value="{{ $tag }}">
            @endforeach
        </form>
    </div>
@endsection

@section('javascripts')
    <script src="{{ asset('js/createLayout.js') }}"></script>
    <script>
        usedTags = {!! $article->tags()->pluck('name') !!}
    </script>
@endsection
