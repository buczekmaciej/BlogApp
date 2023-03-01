<a class="shadow-md rounded-md"
   href="{{ route('articles.view', $article->slug) }}">
    <div class="h-72 w-full rounded-t-md overflow-hidden flex items-center justify-center article-bg-gradient">
        @if ($article->thumbnail)
            <img alt=""
                 class="rounded-md w-full"
                 src="{{ asset('assets/images/' . $article->uuid . '/' . $article->thumbnail) }}">
        @endif
    </div>
    <div class="p-3 flex flex-col justify-between gap-3 h-[calc(100%-18rem)]">
        <p class="text-2xl font-semibold"
           title="{{ ucfirst($article->title) }}">{{ Str::limit(ucfirst($article->title), 40) }}</p>
        <p>{{ $article->created_at->timezone(auth()->user()->timezone ?? 'UTC')->format(isset($format) ? $format : 'M d') }}</p>
    </div>
</a>
