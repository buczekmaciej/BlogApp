@extends('layouts.admin.base')

@section('title', 'Articles')

@section('content')
    <div class="w-3/4 flex flex-col gap-8 py-10">
        <div class="w-full flex flex-col gap-4">
            <div class="w-full flex gap-4 py-4 pl-6 bg-slate-100">
                <p class="font-semibold flex-[2] text-center">UUID</p>
                <p class="font-semibold flex-[4] text-center">Title</p>
                <p class="font-semibold flex-1 text-center">Embeds</p>
                <p class="font-semibold flex-[2] text-center">Author</p>
                <p class="font-semibold flex-[2] text-center">Created</p>
                <p class="font-semibold flex-1 text-center">Updated</p>
                <p class="font-semibold flex-1">Actions</p>
            </div>
            @foreach ($articles as $article)
                <div class="w-full flex gap-4 border-b-[1px] border-solid border-gray-200 last:border-0 py-4 pl-6 first:pt-0">
                    <p class="flex-[2] truncate"
                       title="{{ $article->uuid }}">{{ $article->uuid }}</p>
                    <a class="flex-[4] truncate"
                       href="{{ route('articles.view', $article->slug) }}"
                       target="_blank">{{ $article->title }}</a>
                    <p class="flex-1 text-center">{{ sizeof($article->getEmbeds()) }}</p>
                    <a class="flex-[2] truncate"
                       href="{{ route('user.profile', $article->author->username) }}"
                       target="_blank">{{ $article->author->displayName ? $article->author->displayName . " (@{$article->author->username})" : "@{$article->author->username}" }}</a>
                    <p class="flex-[2] text-center">{{ $article->created_at->format('d/m/Y H:i') }}</p>
                    <p class="flex-1 text-center">{{ $article->updated_at->diffInDays(now()) }}d ago</p>
                    <div class="flex gap-6 items-center flex-1">
                        <a href="{{ route('articles.edit', $article->slug) }}"
                           target="_blank">
                            <svg class="h-4 fill-blue-600"
                                 viewBox="0 0 512 512"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M290.74 93.24l128.02 128.02-277.99 277.99-114.14 12.6C11.35 513.54-1.56 500.62.14 485.34l12.7-114.22 277.9-277.88zm207.2-19.06l-60.11-60.11c-18.75-18.75-49.16-18.75-67.91 0l-56.55 56.55 128.02 128.02 56.55-56.55c18.75-18.76 18.75-49.16 0-67.91z"></path>
                            </svg>
                        </a>
                        <a href="{{ route('admin.articles.delete', $article->slug) }}">
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
        {{ $articles->withQueryString()->links() }}
    </div>
@endsection
