@extends('base')

@section('title', 'Create article')

@section('body')
    <div class="w-3/4 py-10 px-8 my-10 bg-white text-natural-900 flex flex-col items-center gap-8 rounded-md shadow-md">
        <p class="font-bold text-4xl">Build layout</p>
        <form class="flex flex-col gap-3 w-full"
              method="post">
            <div class="flex gap-3">
                <div class="w-3/4 flex flex-col gap-3">
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
                    <textarea class="w-full h-[55vh] outline-transparent resize-none"
                              name="content"
                              required>{!! session()->get('article.content') !!}</textarea>
                </div>
                <div class="flex flex-col w-1/4 gap-3 h-[59vh] overflow-auto px-1">
                    @foreach (session()->get('uploaded') as $key => $img)
                        <div class="flex flex-col items-start gap-2">
                            <p class="text-sm">{{ $img }}</p>
                            <img alt=""
                                 class=""
                                 src="{{ asset('assets/uploads/' . $img) }}">
                            <div class="flex items-center gap-2 cursor-pointer [&>*]:cursor-pointer">
                                <input @if ($loop->first) checked @endif
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
            <div class="flex justify-between">
                <a class="form-btn self-start"
                   href="{{ route('articles.createImages') }}">Back to images</a>
                <button class="form-btn">Create article</button>
            </div>
            @csrf
        </form>
    </div>
@endsection

@section('javascripts')
    <script src="{{ asset('js/createLayout.js') }}"></script>
@endsection
