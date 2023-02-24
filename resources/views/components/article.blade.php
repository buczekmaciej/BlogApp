<a class="shadow-md rounded-md"
   href="{{ route('articles.view', $article->slug) }}">
    <div class="h-72 w-full rounded-t-md {{ $article->thumbnail ? 'bg-[url(\'/assets/images/' . $article->uuid . $article->thumbnail . '\')] bg-cover bg-center' : 'article-bg-gradient' }}"></div>
    <div class="p-3 flex flex-col gap-3">
        <p class="text-2xl font-semibold">{{ Str::limit(ucfirst($article->title), 100) }}</p>
        <p class="">{{ $article->created_at->format('M d') }}</p>
    </div>
</a>
