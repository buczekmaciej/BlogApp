<a class="shadow-md rounded-md"
   href="{{ route('articles.view', $article->slug) }}">
    <div class="h-72 w-full rounded-t-md {{ $article->thumbnail ? 'bg-[url(\'/assets/images/' . $article->uuid . $article->thumbnail . '\')] bg-cover bg-center' : 'article-bg-gradient' }}"></div>
    <div class="p-3 flex flex-col justify-between gap-3 h-[calc(100%-18rem)]">
        <p class="text-2xl font-semibold"
           title="{{ ucfirst($article->title) }}">{{ Str::limit(ucfirst($article->title), 40) }}</p>
        <p>{{ $article->created_at->format(isset($format) ? $format : 'M d') }}</p>
    </div>
</a>
