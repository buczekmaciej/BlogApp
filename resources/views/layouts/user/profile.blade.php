@extends('base')

@section('title', $user->getName())

@section('body')
    <div class="w-1/2 py-16 flex flex-col gap-6">
        <div class="flex items-center gap-10">
            <img alt=""
                 class="h-72 rounded-md"
                 src="{{ asset('assets/profileImages/' . $user->image) }}">
            <div class="flex flex-col items-start gap-4">
                @if (auth()->user()->username !== $user->username)
                    @php
                        $isFollowing = auth()
                            ->user()
                            ->following()
                            ->get()
                            ->contains($user);
                    @endphp
                    <a class="px-3 py-1 rounded-md border-2 border-solid {{ $isFollowing ? 'bg-blue-900 text-slate-50 border-blue-900' : 'border-blue-800 text-blue-800' }}"
                       href="{{ route('user.newFollow', $user->username) }}">{{ $isFollowing ? 'Following' : 'Follow' }}</a>
                @endif
                @if ($user->displayName)
                    <div class="flex flex-col gap-1">
                        @if ($user->isWriter())
                            <p class="flex items-center gap-4">
                                <span class="text-2xl">{{ $user->displayName }}</span>
                                <span class="{{ $user->isAdmin() ? 'bg-fuchsia-800/10 text-fuchsia-800' : 'bg-orange-600/10 text-orange-600' }} text-sm font-bold px-2 py-1.5 rounded-md">{{ $user->getRole() }}</span>
                            </p>
                        @else
                            <p class="text-2xl">{{ $user->displayName }}</p>
                        @endif
                        <p class="text-gray-400 text-sm">{{ '@' . $user->username }}</p>
                    </div>
                @else
                    <p class="text-2xl">{{ $user->username }}</p>
                @endif
                <p class="w-full text-sm flex gap-10 items-center">
                    <span>{{ $user->articles()->count() }} articles</span>
                    <span>{{ $user->followedBy()->count() }} followers</span>
                    <span>Following {{ $user->following()->count() }}</span>
                </p>
                @if ($user->bio)
                    <p>{{ $user->bio }}</p>
                @endif
                @if ($user->username === auth()->user()->username)
                    <a class="px-3 py-1 mt-3 rounded-md border-2 border-solid border-gray-400 text-gray-400"
                       href="{{ route('user.settings') }}">Settings</a>
                @endif
            </div>
        </div>
        <div class="flex flex-col gap-6">
            <div class="flex items-center gap-4 py-3 border-b-2 border-solid border-gray-200">
                @if ($user->isWriter())
                    <a class="{{ $view === 'articles' ? 'profile-active' : '' }}"
                       href="{{ route('user.profile', ['user' => $user->username, 'view' => 'articles']) }}">Articles</a>
                @endif
                <a class="{{ $view === 'comments' ? 'profile-active' : '' }}"
                   href="{{ route('user.profile', ['user' => $user->username, 'view' => 'comments']) }}">Comments</a>
                <a class="{{ $view === 'followers' ? 'profile-active' : '' }}"
                   href="{{ route('user.profile', ['user' => $user->username, 'view' => 'followers']) }}">Followers</a>
                <a class="{{ $view === 'following' ? 'profile-active' : '' }}"
                   href="{{ route('user.profile', ['user' => $user->username, 'view' => 'following']) }}">Following</a>
            </div>
            @include('components.profile', ['data' => $data, 'view' => $view])
        </div>
    </div>
@endsection
