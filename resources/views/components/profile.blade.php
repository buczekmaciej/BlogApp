@if ($view === 'articles')
    @forelse ($data as $article)
        @include('components.article', ['article' => $article, 'format' => 'F d, Y | H:i:s'])
    @empty
        <p class="font-semibold text-lg">Nothing to show here</p>
    @endforelse
    {{ $data->links() }}
@elseif($view === 'comments')
    @forelse ($data as $comment)
        <div class="flex flex-col gap-3 shadow-md rounded-md px-3 py-4">
            <p class="flex justify-between items-center text-sm">
                <span>Commented in <a class="text-blue-900 font-bold"
                       href="{{ route('articles.view', $comment->article()->first()->slug) }}">article</a>:</span>
                <span>{{ $comment->created_at->timezone(auth()->user()->timezone ?? 'UTC')->format('M d, Y | H:i') }}</span>
            </p>
            <p class="text-xl">{{ $comment->content }}</p>
        </div>
    @empty
        <p class="font-semibold text-lg">Nothing to show here</p>
    @endforelse
    {{ $data->links() }}
@elseif ($view === 'followers' || $view === 'following')
    @forelse ($data as $user)
        <div class="flex items-center justify-between">
            <a class="flex items-center gap-3"
               href="{{ route('user.profile', $user->username) }}">
                <img alt=""
                     class="h-16 rounded-md"
                     src="{{ asset('assets/profileImages/' . $user->image) }}">
                <p>{{ $user->username }}</p>
            </a>
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
        </div>
    @empty
        <p class="font-semibold text-lg">Nothing to show here</p>
    @endforelse
    {{ $data->links() }}
@endif
