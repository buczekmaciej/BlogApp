@extends('base')

@section('title', 'Authors')

@section('body')
    <div class="px-24 py-10 w-full flex flex-col gap-6">
        <p class="text-4xl font-semibold">Our authors</p>
        <div class="grid grid-cols-2 gap-5">
            @foreach ($authors as $author)
                <div class="flex items-center shadow-md rounded-md">
                    <img alt=""
                         class="h-48 rounded-l-md"
                         src="{{ asset('assets/profileImages/' . $author->image) }}">
                    <div class="px-4 w-full flex flex-wrap justify-between gap-8">
                        @if ($author->displayName)
                            <p class="flex flex-col gap-1">
                                <span class="text-2xl">{{ $author->displayName }}</span>
                                <span class="text-gray-400 text-sm">{{ '@' . $author->username }}</span>
                            </p>
                        @else
                            <p class="text-2xl">{{ $author->username }}</p>
                        @endif
                        <a class="fill-blue-900"
                           href="{{ route('authors.view', $author->username) }}">
                            <svg class="h-4"
                                 viewBox="0 0 512 512"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M432,320H400a16,16,0,0,0-16,16V448H64V128H208a16,16,0,0,0,16-16V80a16,16,0,0,0-16-16H48A48,48,0,0,0,0,112V464a48,48,0,0,0,48,48H400a48,48,0,0,0,48-48V336A16,16,0,0,0,432,320ZM488,0h-128c-21.37,0-32.05,25.91-17,41l35.73,35.73L135,320.37a24,24,0,0,0,0,34L157.67,377a24,24,0,0,0,34,0L435.28,133.32,471,169c15,15,41,4.5,41-17V24A24,24,0,0,0,488,0Z"></path>
                            </svg>
                        </a>
                        <p class="w-full text-sm flex gap-10 items-center">
                            <span>{{ $author->articles()->count() }} articles</span>
                            <span>{{ $author->followedBy()->count() }} followers</span>
                            <span>Following {{ $author->following()->count() }}</span>
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
