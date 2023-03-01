@extends('base')

@section('title', ucfirst($article->title))

@section('body')
    <div class="px-24 py-10 w-full flex flex-col gap-8">
        <div class="px-16 flex flex-col items-center gap-12">
            <div class="relative flex flex-col items-center gap-4 w-full">
                <p class="text-gray-400">Published {{ $article->created_at->format('F d, Y') }} by <a class="text-blue-800"
                       href="{{ route('user.profile', $article->author()->first()->username) }}">{{ $article->author()->first()->getName() }}</a></p>
                <p class="font-bold text-5xl w-2/3 text-center">{{ ucfirst($article->title) }}</p>
            </div>
            <img alt=""
                 src="{{ asset('assets/images/' . $article->uuid . '/' . $article->thumbnail) }}">
            <div class="h-96 relative w-full rounded-md {{ $article->thumbnail ? "bg-[url('assets/images/{$article->uuid}/{$article->thumbnail}')] bg-cover bg-center" : 'article-bg-gradient' }}">
                @if (auth()->user())
                    @can('update', $article)
                        <div class="absolute top-6 right-6 flex gap-4 [&>a]:rounded-md [&>a]:bg-slate-50/30 [&>a]:p-3">
                            <a href="{{ route('articles.edit', $article->slug) }}">
                                <svg class="h-7 fill-blue-100"
                                     viewBox="0 0 512 512"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path d="M290.74 93.24l128.02 128.02-277.99 277.99-114.14 12.6C11.35 513.54-1.56 500.62.14 485.34l12.7-114.22 277.9-277.88zm207.2-19.06l-60.11-60.11c-18.75-18.75-49.16-18.75-67.91 0l-56.55 56.55 128.02 128.02 56.55-56.55c18.75-18.76 18.75-49.16 0-67.91z"></path>
                                </svg>
                            </a>
                            <a href="{{ route('articles.bookmark', $article->slug) }}">
                                <svg class="h-7 fill-blue-100"
                                     viewBox="0 0 384 512"
                                     xmlns="http://www.w3.org/2000/svg">
                                    @if (auth()->user()->bookmarks()->get()->contains($article))
                                        <path d="M0 512V48C0 21.49 21.49 0 48 0h288c26.51 0 48 21.49 48 48v464L192 400 0 512z"></path>
                                    @else
                                        <path d="M336 0H48C21.49 0 0 21.49 0 48v464l192-112 192 112V48c0-26.51-21.49-48-48-48zm0 428.43l-144-84-144 84V54a6 6 0 0 1 6-6h276c3.314 0 6 2.683 6 5.996V428.43z"></path>
                                    @endif
                                </svg>
                            </a>
                        </div>
                    @else
                        <a class="absolute top-6 right-6 rounded-md bg-slate-50/30 p-3"
                           href="{{ route('articles.bookmark', $article->slug) }}">
                            <svg class="h-7 fill-blue-100"
                                 viewBox="0 0 384 512"
                                 xmlns="http://www.w3.org/2000/svg">
                                @if (auth()->user()->bookmarks()->get()->contains($article))
                                    <path d="M0 512V48C0 21.49 21.49 0 48 0h288c26.51 0 48 21.49 48 48v464L192 400 0 512z"></path>
                                @else
                                    <path d="M336 0H48C21.49 0 0 21.49 0 48v464l192-112 192 112V48c0-26.51-21.49-48-48-48zm0 428.43l-144-84-144 84V54a6 6 0 0 1 6-6h276c3.314 0 6 2.683 6 5.996V428.43z"></path>
                                @endif
                            </svg>
                        </a>
                    @endcan
                @endif
            </div>
            <div class="px-20 text-lg">{!! Markdown::parse(nl2br($article->content)) !!}</div>
            @if (auth()->user())
                @if ($article->likes()->get()->contains(auth()->user()))
                    <a class="flex gap-3 bg-red-700 text-slate-50 px-6 py-3 rounded-md"
                       href="{{ route('articles.like', $article->slug) }}">
                        <svg class="h-7 fill-slate-50"
                             viewBox="0 0 512 512"
                             xmlns="http://www.w3.org/2000/svg">
                            <path d="M462.3 62.6C407.5 15.9 326 24.3 275.7 76.2L256 96.5l-19.7-20.3C186.1 24.3 104.5 15.9 49.7 62.6c-62.8 53.6-66.1 149.8-9.9 207.9l193.5 199.8c12.5 12.9 32.8 12.9 45.3 0l193.5-199.8c56.3-58.1 53-154.3-9.8-207.9z"></path>
                        </svg>
                        <span>You love it!</span>
                    </a>
                @else
                    <a class="flex gap-3 bg-red-700/10 text-red-700 px-6 py-3 rounded-md"
                       href="{{ route('articles.like', $article->slug) }}">
                        <svg class="h-7 fill-red-700"
                             viewBox="0 0 512 512"
                             xmlns="http://www.w3.org/2000/svg">
                            <path d="M458.4 64.3C400.6 15.7 311.3 23 256 79.3 200.7 23 111.4 15.6 53.6 64.3-21.6 127.6-10.6 230.8 43 285.5l175.4 178.7c10 10.2 23.4 15.9 37.6 15.9 14.3 0 27.6-5.6 37.6-15.8L469 285.6c53.5-54.7 64.7-157.9-10.6-221.3zm-23.6 187.5L259.4 430.5c-2.4 2.4-4.4 2.4-6.8 0L77.2 251.8c-36.5-37.2-43.9-107.6 7.3-150.7 38.9-32.7 98.9-27.8 136.5 10.5l35 35.7 35-35.7c37.8-38.5 97.8-43.2 136.5-10.6 51.1 43.1 43.5 113.9 7.3 150.8z"></path>
                        </svg>
                        <span>Love it</span>
                    </a>
                @endif
            @endif
        </div>
        <div class="px-16 flex flex-col gap-4">
            <p class="font-semibold text-3xl">Comments</p>
            @if (auth()->user())
                <form action="{{ route('articles.newComment', $article->slug) }}"
                      autocomplete="off"
                      class="w-full border-2 border-solid border-gray-200 rounded-sm p-3 flex flex-wrap items-center justify-between gap-3"
                      method="POST">
                    <p class="font-semibold text-xl">{{ auth()->user()->getName() }} says:</p>
                    <button class="bg-blue-700 text-slate-50 rounded-md px-3 py-2">Share</button>
                    <textarea class="w-full resize-none h-40 border-b-2 border-solid border-gray-150 bg-transparent outline-transparent focus:border-gray-600"
                              name="content"
                              placeholder="Your opinion"></textarea>
                    @csrf
                </form>
            @else
                <p class="text-sm">To comment article you need to <a class="text-blue-700 font-semibold"
                       href="{{ route('security.login') }}">login</a></p>
            @endif
            <div class="flex flex-col gap-6 px-20 pt-8">
                @foreach ($article->comments()->orderBy('created_at', 'DESC')->get() as $comment)
                    <div class="flex flex-wrap justify-between items-center border-b-[1px] border-solid border-gray-300 pb-6">
                        <a class="flex gap-2 items-center"
                           href="{{ route('user.profile', $comment->author()->first()->username) }}">
                            <img alt=""
                                 class="h-10 rounded-md"
                                 src="{{ asset('assets/profileImages/' . $comment->author()->first()->image) }}">
                            <span class="text-lg">{{ $comment->author()->first()->getName() }}</span>
                        </a>
                        <p class="">{{ $comment->created_at->timezone(auth()->user()?->timezone ?? 'UTC')->format('M d, Y | H:i:s') }}</p>
                        <p class="w-full mt-4 px-12 text-2xl">{!! $comment->content !!}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
