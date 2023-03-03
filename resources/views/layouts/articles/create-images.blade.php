@extends('base')

@section('title', 'Upload images')

@section('body')
    <div class="w-1/2 py-10 px-8 my-10 bg-white text-natural-900 flex flex-col items-center gap-8 rounded-md shadow-md">
        <p class="font-bold text-4xl">Upload images <sup class="text-red-600">*</sup></p>
        <form class="flex flex-col items-center gap-4 w-full"
              enctype="multipart/form-data"
              method="post">
            <div class="flex flex-col items-center gap-2">
                <input @if (!session()->has('uploaded')) required @endif
                       accept="image/png, image/jpeg"
                       class="files border-2 border-solid border-gray-100 rounded-md p-2 outline-transparent cursor-pointer"
                       multiple
                       name="files[]"
                       type="file">
                <span class='input-help'>Max 5MB, only .png, .jpg, .jpeg</span>
            </div>
            <div class="previews flex flex-col gap-4">
                @if (session()->get('uploaded'))
                    <div>
                        <p class="font-semibold">Previously uploaded</p>
                        <div class="grid grid-cols-3 grid-rows-1 items-center gap-3">
                            @foreach (session()->get('uploaded') as $img)
                                <div class="flex flex-wrap justify-between items-center gap-2"
                                     data-name="{{ $img }}">
                                    <p class="w-3/4 truncate">{{ $img }}</p>
                                    <a class=""
                                       href="{{ route('articles.removeImage', ['filename' => $img]) }}">
                                        <svg class="remove-img h-4"
                                             viewBox="0 0 352 512"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path d="M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z"></path>
                                        </svg>
                                    </a>
                                    <img alt="Uploaded file"
                                         src="{{ asset('assets/uploads/' . $img) }}">
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                <div>
                    <p class="font-semibold hidden">Preview</p>
                    <div class="preview-list grid grid-cols-4 grid-rows-1 items-center gap-3"></div>
                </div>
            </div>
            <div class="w-full flex justify-between">
                <a class="form-btn"
                   href="{{ route('articles.create') }}">Back to content</a>
                <button class="form-btn">Build layout</button>
            </div>
            @csrf
        </form>

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <p class="font-medium text-red-500">{{ $error }}</p>
            @endforeach
        @endif
    </div>
@endsection

@section('javascripts')
    <script src="{{ asset('js/createImages.js') }}"></script>
@endsection
