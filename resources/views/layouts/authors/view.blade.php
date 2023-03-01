@extends('base')

@section('title', $author->getName())

@section('body')
    <div class="w-1/2 py-16 flex flex-col gap-6">
        <div class="flex gap-2 items-center">
            <a class="underline text-blue-800 font-semibold"
               href="{{ route('authors.list') }}">Authors</a>
            <svg class="h-4 fill-blue-800"
                 viewBox="0 0 256 512"
                 xmlns="http://www.w3.org/2000/svg">
                <path d="M224.3 273l-136 136c-9.4 9.4-24.6 9.4-33.9 0l-22.6-22.6c-9.4-9.4-9.4-24.6 0-33.9l96.4-96.4-96.4-96.4c-9.4-9.4-9.4-24.6 0-33.9L54.3 103c9.4-9.4 24.6-9.4 33.9 0l136 136c9.5 9.4 9.5 24.6.1 34z"></path>
            </svg>
            <p class="">{{ $author->username }}</p>
        </div>
        <div class="flex items-center gap-10">
            <img alt=""
                 class="h-72 rounded-md"
                 src="{{ asset('assets/profileImages/' . $author->image) }}">
            <div class="flex flex-col items-start gap-4">
                @if (auth()->user()->username !== $author->username)
                    @php
                        $isFollowing = auth()
                            ->user()
                            ->following()
                            ->get()
                            ->contains($author);
                    @endphp
                    <a class="px-3 py-1 rounded-md border-2 border-solid {{ $isFollowing ? 'bg-blue-900 text-slate-50 border-blue-900' : 'border-blue-800 text-blue-800' }}"
                       href="{{ route('user.newFollow', $author->username) }}">{{ $isFollowing ? 'Following' : 'Follow' }}</a>
                @endif
                @if ($author->displayName)
                    <p class="flex flex-col gap-1">
                        <span class="text-2xl">{{ $author->displayName }}</span>
                        <span class="text-gray-400 text-sm">{{ '@' . $author->username }}</span>
                    </p>
                @else
                    <p class="text-2xl">{{ $author->username }}</p>
                @endif
                <p class="w-full text-sm flex gap-10 items-center">
                    <span>{{ $author->articles()->count() }} articles</span>
                    <span>{{ $author->followedBy()->count() }} followers</span>
                    <span>Following {{ $author->following()->count() }}</span>
                </p>
            </div>
        </div>
        <div class="flex flex-col gap-6">
            @foreach ($articles as $article)
                @include('components.article', ['article' => $article, 'format' => 'F d, Y | H:i:s'])
            @endforeach
            {{ $articles->links() }}
        </div>
    </div>
@endsection
