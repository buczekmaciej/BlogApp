@extends('layouts.admin.base')

@section('title', 'Warrants')

@section('content')
    <div class="bg-container {{ $errors->any() ? 'grid' : 'hidden' }} fixed grid place-items-center top-0 left-0 z-40 bg-neutral-800/90 h-screen w-full">
        <form action="{{ route('admin.warrants.create') }}"
              class="create-form w-1/4 p-8 hidden flex-col gap-6 rounded-md bg-slate-50"
              method="POST"
              name="createForm">
            @csrf
            <p class="text-xl font-semibold">Create warrant</p>
            <div class="flex flex-col gap-2">
                <label class="text-lg"
                       for="reason">
                    Reason
                    <sup class="text-red-600">*</sup>
                </label>
                <select class="form-input cursor-pointer"
                        id="reason"
                        name="reason"
                        required>
                    <option value="Inappropriate content">Inappropriate content</option>
                    <option value="Copyright reason">Copyright reason</option>
                </select>
            </div>
            @include('components.form-box', [
                'error' => '',
                'id' => 'article_uuid',
                'label' => 'Article uuid',
                'name' => 'article_uuid',
                'value' => '',
            ])
            <div class="flex items-center justify-end gap-4 w-full">
                <button class="create-close"
                        type="button">Close</button>
                <button class="form-btn">Apply</button>
            </div>
        </form>
        <form action="{{ route('admin.warrants.edit', 'warrant') }}"
              class="edit-form w-1/4 p-8 hidden flex-col gap-6 rounded-md bg-slate-50"
              method="POST"
              name="editForm">
            @csrf
            <p class="text-xl font-semibold">Edit warrant</p>
            <div class="flex flex-col gap-2">
                <label class="text-lg"
                       for="reason">
                    Reason
                    <sup class="text-red-600">*</sup>
                </label>
                <select class="form-input cursor-pointer"
                        id="reason"
                        name="reason"
                        required>
                    <option value="Inappropriate content">Inappropriate content</option>
                    <option value="Copyright reason">Copyright reason</option>
                </select>
            </div>
            @include('components.form-box', [
                'error' => '',
                'id' => 'warrant_uuid_edit',
                'label' => 'Warrant uuid',
                'name' => 'warrant_uuid',
                'value' => '',
                'disabled' => true,
            ])
            <div class="flex items-center justify-end gap-4 w-full">
                <button class="edit-close"
                        type="button">Close</button>
                <button class="form-btn">Apply</button>
            </div>
        </form>
    </div>
    <div class="w-3/4 flex flex-col gap-8 py-10">
        <div class="flex items-center justify-start gap-4">
            {!! $warrants->links('vendor.pagination.tailwind', ['onlyCounter' => true]) !!}
            <form class="flex items-center gap-2 ml-auto">
                <p class="">Order by:</p>
                @include('components.sort-form', [
                    'options' => [
                        ['value' => 'uuid-asc', 'view' => 'UUID growing'],
                        ['value' => 'uuid-desc', 'view' => 'UUID decreasing'],
                        ['value' => 'reason-asc', 'view' => 'Reason growing'],
                        ['value' => 'reason-desc', 'view' => 'Reason decreasing'],
                        ['value' => 'created_at-asc', 'view' => 'Created growing'],
                        ['value' => 'created_at-desc', 'view' => 'Created decreasing'],
                        ['value' => 'updated_at-asc', 'view' => 'Updated growing'],
                        ['value' => 'updated_at-desc', 'view' => 'Updated decreasing'],
                    ],
                    'selectKey' => 'order',
                    'default' => 'created_at-desc',
                    'excludedKeys' => ['order'],
                ])
            </form>
            <p class="form-btn cursor-pointer create-btn">Create warrant</p>
        </div>
        <div class="w-full flex flex-col gap-4">
            <div class="w-full flex gap-4 py-4 pl-6 bg-slate-100">
                <p class="font-semibold flex-[2] text-center">UUID</p>
                <p class="font-semibold flex-[3] text-center">Reason</p>
                <p class="font-semibold flex-[2] text-center">Author</p>
                <p class="font-semibold flex-1 text-center">Article</p>
                <p class="font-semibold flex-[2] text-center">Created</p>
                <p class="font-semibold flex-1 text-center">Updated</p>
                <p class="font-semibold flex-1 text-center">Actions</p>
            </div>
            @forelse ($warrants as $warrant)
                <div class="w-full flex gap-4 border-b-[1px] border-solid border-gray-200 group last:border-0 py-4 pl-6 first:pt-0">
                    <p class="flex-[2] truncate">{{ $warrant->uuid }}</p>
                    <p class="flex-[3]">{{ $warrant->reason }}</p>
                    @if ($warrant->author)
                        <a class="flex-[2] truncate text-center text-blue-800 font-bold"
                           href="{{ route('user.profile', $warrant->author->username) }}"
                           target="_blank">{{ $warrant->author->displayName ? $warrant->author->displayName . " (@{$warrant->author->username})" : "@{$warrant->author->username}" }}</a>
                    @else
                        <p class="flex-[2] text-center text-gray-400">Deleted user</p>
                    @endif
                    <a class="flex-1 text-center text-blue-800 font-bold"
                       href="{{ route('articles.view', $warrant->article->slug) }}"
                       target="_blank">Open article</a>
                    <p class="flex-[2] text-center">{{ $warrant->created_at->format('d/m/Y H:i') }}</p>
                    <p class="flex-1 text-center">{{ $warrant->updated_at->diffInDays(now()) }}d ago</p>
                    <div class="flex gap-6 items-center justify-center flex-1 opacity-0 group-hover:opacity-100">
                        <p class="edit-btn cursor-pointer"
                           data-id="{{ $warrant->uuid }}">
                            <svg class="h-4 fill-blue-600"
                                 viewBox="0 0 512 512"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M290.74 93.24l128.02 128.02-277.99 277.99-114.14 12.6C11.35 513.54-1.56 500.62.14 485.34l12.7-114.22 277.9-277.88zm207.2-19.06l-60.11-60.11c-18.75-18.75-49.16-18.75-67.91 0l-56.55 56.55 128.02 128.02 56.55-56.55c18.75-18.76 18.75-49.16 0-67.91z"></path>
                            </svg>
                        </p>
                        <a href="{{ route('admin.warrants.delete', $warrant->uuid) }}">
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
        {!! $warrants->withQueryString()->links() !!}
    </div>
@endsection

@section('javascripts')
    <script src="{{ asset('js/select.js') }}"></script>
    <script src="{{ asset('js/adminWarrants.js') }}"></script>
@endsection
