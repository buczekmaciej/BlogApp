@extends('layouts.admin.base')

@section('title', 'Tags')

@section('content')
    <div class="bg-container {{ $errors->any() ? 'grid' : 'hidden' }} fixed grid place-items-center top-0 left-0 z-40 bg-neutral-800/90 h-screen w-full">
        <form action="{{ route('admin.tags.create') }}"
              class="create-form w-1/4 p-8 {{ $errors->has('tag') ? 'flex' : 'hidden' }} flex-col gap-6 rounded-md bg-slate-50"
              method="POST"
              name="createTag">
            @csrf
            <p class="text-xl font-semibold">Create tag</p>
            @include('components.form-box', [
                'error' => $errors->has('create') ? $errors->first('create') : null,
                'label' => 'New tag name',
                'id' => 'createTag',
                'name' => 'tag',
                'value' => old('tag'),
            ])
            <div class="flex items-center justify-end gap-4 w-full">
                <button class="create-close"
                        type="button">Close</button>
                <button class="form-btn">Create</button>
            </div>
        </form>
        <form action="{{ route('admin.tags.edit', 'name') }}"
              class="edit-form w-1/4 p-8 {{ $errors->has('uuid') || $errors->has('name') ? 'flex' : 'hidden' }} flex-col gap-6 rounded-md bg-slate-50"
              method="POST"
              name="editTag">
            @csrf
            <p class="text-xl font-semibold">Edit tag</p>
            @include('components.form-box', [
                'error' => $errors->has('name') ? $errors->first('name') : null,
                'label' => 'Tag name',
                'id' => 'tagName',
                'name' => 'name',
                'value' => old('name'),
            ])
            <div class="flex items-center justify-end gap-4 w-full">
                <button class="edit-close"
                        type="button">Close</button>
                <button class="form-btn">Update</button>
            </div>
        </form>
    </div>
    <div class="w-3/4 flex flex-col gap-8 py-10">
        <div class="flex items-center justify-start gap-4">
            {!! $tags->links('vendor.pagination.tailwind', ['onlyCounter' => true]) !!}
            <form class="flex items-center gap-2 ml-auto">
                <p class="">Order by:</p>
                @include('components.sort-form', [
                    'options' => [['value' => 'uuid-asc', 'view' => 'UUID growing'], ['value' => 'uuid-desc', 'view' => 'UUID decreasing'], ['value' => 'name-asc', 'view' => 'Name growing'], ['value' => 'name-desc', 'view' => 'Name decreasing'], ['value' => 'articles_count-asc', 'view' => 'Articles growing'], ['value' => 'articles_count-desc', 'view' => 'Articles decreasing']],
                    'selectKey' => 'order',
                    'default' => 'name-asc',
                    'excludedKeys' => ['order'],
                ])
            </form>
            <p class="create-tag cursor-pointer form-btn">Create tag</p>
        </div>
        <div class="w-full flex flex-col gap-4">
            <div class="w-full flex gap-4 py-4 pl-6 bg-slate-100">
                <p class="font-semibold flex-[3] text-center">UUID</p>
                <p class="font-semibold flex-[3] text-center">Name</p>
                <p class="font-semibold flex-[2] text-center">Articles</p>
                <p class="font-semibold flex-1 text-center">Actions</p>
            </div>
            @forelse ($tags as $tag)
                <div class="w-full flex gap-4 border-b-[1px] border-solid border-gray-200 group last:border-0 py-4 pl-6 first:pt-0">
                    <p class="flex-[3]">{{ $tag->uuid }}</p>
                    <p class="flex-[3]">{{ $tag->name }}</p>
                    <p class="flex-[2] text-center">{{ $tag->articles->count() }}</p>
                    <div class="flex gap-6 items-center justify-center flex-1 opacity-0 group-hover:opacity-100">
                        <p class="edit-btn cursor-pointer"
                           data-id="{{ $tag->uuid }}">
                            <svg class="h-4 fill-blue-600"
                                 viewBox="0 0 512 512"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M290.74 93.24l128.02 128.02-277.99 277.99-114.14 12.6C11.35 513.54-1.56 500.62.14 485.34l12.7-114.22 277.9-277.88zm207.2-19.06l-60.11-60.11c-18.75-18.75-49.16-18.75-67.91 0l-56.55 56.55 128.02 128.02 56.55-56.55c18.75-18.76 18.75-49.16 0-67.91z"></path>
                            </svg>
                        </p>
                        <a href="{{ route('admin.tags.delete', $tag->uuid) }}">
                            <svg class="h-4 fill-red-600"
                                 viewBox="0 0 448 512"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M432 32H312l-9.4-18.7A24 24 0 0 0 281.1 0H166.8a23.72 23.72 0 0 0-21.4 13.3L136 32H16A16 16 0 0 0 0 48v32a16 16 0 0 0 16 16h416a16 16 0 0 0 16-16V48a16 16 0 0 0-16-16zM53.2 467a48 48 0 0 0 47.9 45h245.8a48 48 0 0 0 47.9-45L416 128H32z"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            @empty
                <div class="w-full text-center py-4">
                    <p>There's nothing to show</p>
                </div>
            @endforelse
        </div>
        {!! $tags->withQueryString()->links() !!}
    </div>
@endsection

@section('javascripts')
    <script src="{{ asset('js/select.js') }}"></script>
    <script src="{{ asset('js/adminTags.js') }}"></script>
@endsection
