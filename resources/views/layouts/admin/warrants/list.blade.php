@extends('layouts.admin.base')

@section('title', 'Tags')

@section('content')
    <div class="w-3/4 flex flex-col gap-8 py-10">
        <div class="flex items-center justify-start gap-4">
            {!! $comments->links('vendor.pagination.tailwind', ['onlyCounter' => true]) !!}
            <form class="flex items-center gap-2 ml-auto">
                <p class="">Order by:</p>
                @include('components.sort-form', [
                    'options' => [
                        ['value' => 'comments.uuid-asc', 'view' => 'UUID growing'],
                        ['value' => 'comments.uuid-desc', 'view' => 'UUID decreasing'],
                        ['value' => 'content-asc', 'view' => 'Content growing'],
                        ['value' => 'content-desc', 'view' => 'Content decreasing'],
                        ['value' => 'users.username-asc', 'view' => 'Author growing'],
                        ['value' => 'users.username-desc', 'view' => 'Author decreasing'],
                        ['value' => 'created_at-asc', 'view' => 'Created growing'],
                        ['value' => 'created_at-desc', 'view' => 'Created decreasing'],
                    ],
                    'selectKey' => 'order',
                    'default' => 'created_at-desc',
                    'excludedKeys' => ['order'],
                ])
            </form>
        </div>
        <div class="w-full flex flex-col gap-4">
            <div class="w-full flex gap-4 py-4 pl-6 bg-slate-100">
                <p class="font-semibold flex-[2] text-center">UUID</p>
                <p class="font-semibold flex-[5] text-center">Content</p>
                <p class="font-semibold flex-[2] text-center">Author</p>
                <p class="font-semibold flex-1 text-center">Article</p>
                <p class="font-semibold flex-[2] text-center">Created</p>
                <p class="font-semibold flex-1 text-center">Actions</p>
            </div>
            @foreach ($comments as $comment)
                <div class="w-full flex gap-4 border-b-[1px] border-solid border-gray-200 last:border-0 py-4 pl-6 first:pt-0">
                    <p class="flex-[2] truncate">{{ $comment->uuid }}</p>
                    <p class="flex-[5]">{{ $comment->content }}</p>
                    @if ($comment->author)
                        <a class="flex-[2] truncate text-center text-blue-800 font-bold"
                           href="{{ route('user.profile', $comment->author->username) }}"
                           target="_blank">{{ $comment->author->displayName ? $comment->author->displayName . " (@{$comment->author->username})" : "@{$comment->author->username}" }}</a>
                    @else
                        <p class="flex-[2] text-center text-gray-400">Deleted user</p>
                    @endif
                    <a class="flex-1 text-center text-blue-800 font-bold"
                       href="{{ route('articles.view', $comment->article->slug) }}"
                       target="_blank">Open article</a>
                    <p class="flex-[2] text-center">{{ $comment->created_at->format('d/m/Y H:i') }}</p>
                    <div class="flex items-center justify-center flex-1 opacity-0 group-hover:opacity-100">
                        <a href="{{ route('admin.comments.delete', $comment->uuid) }}">
                            <svg class="h-4 fill-red-600"
                                 viewBox="0 0 448 512"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M432 32H312l-9.4-18.7A24 24 0 0 0 281.1 0H166.8a23.72 23.72 0 0 0-21.4 13.3L136 32H16A16 16 0 0 0 0 48v32a16 16 0 0 0 16 16h416a16 16 0 0 0 16-16V48a16 16 0 0 0-16-16zM53.2 467a48 48 0 0 0 47.9 45h245.8a48 48 0 0 0 47.9-45L416 128H32z"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
        {!! $comments->withQueryString()->links() !!}
    </div>
@endsection

@section('javascripts')
    <script src="{{ asset('js/select.js') }}"></script>
@endsection
