@extends('base')

@section('title', "Results for \"$query\"")

@section('body')
    <div class="px-24 py-10 w-full flex flex-col gap-6">
        <p class="text-4xl font-semibold">All we got for "{{ $query }}"</p>
        @if ($results['tags']->count() > 0)
            <div class="flex flex-col gap-4">
                <p class="font-semibold text-2xl">Tags</p>
                <div class="flex flex-wrap gap-2">
                    @foreach ($results['tags'] as $tag)
                        <a class="bg-blue-800/5 text-blue-800 font-medium px-3 py-1 rounded-md"
                           href="{{ route('tags.view', $tag->name) }}">#{{ $tag->name }}</a>
                    @endforeach
                </div>
            </div>
        @endif
        @if ($results['users']->count() > 0)
            <div class="flex flex-col gap-4">
                <p class="font-semibold text-2xl">Users</p>
                <div class="flex flex-wrap gap-3">
                    @foreach ($results['users'] as $user)
                        <a class="flex items-center gap-2 hover:bg-blue-800/5 hover:text-blue-800 pr-3 rounded-md"
                           href="{{ route('user.profile', $user->username) }}">
                            <img alt=""
                                 class="h-12 rounded-md"
                                 src="{{ asset('assets/profileImages/' . $user->image) }}">
                            <span>{{ $user->getName() }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
        @if ($results['articles']->count() > 0)
            <div class="flex flex-col gap-4">
                <p class="font-semibold text-2xl">Articles</p>
                <div class="grid grid-cols-3 gap-4">
                    @foreach ($results['articles'] as $article)
                        @include('components.article', ['article' => $article, 'format' => 'F d, Y'])
                    @endforeach
                </div>
                {{ $results['articles']->withQueryString()->links() }}
            </div>
        @endif
    </div>
@endsection
