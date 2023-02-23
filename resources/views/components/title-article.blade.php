<div class="w-full h-[30rem] px-24 py-8 text-slate-50 flex flex-col items-start justify-end gap-4 {{ $article->thumbnail ? 'bg-[url(\'/assets/images/' . $article->uuid . $article->thumbnail . '\')] bg-cover' : 'article-bg-gradient' }}">
    <div class="flex gap-4 items-center">
        <a class="bg-indigo-900 capitalize px-3 py-1 rounded-md"
           href="{{ route('tags.view', $article->tags()->first()->name) }}">{{ $article->tags()->first()->name }}</a>
        <a class=""
           href="{{ route('authors.view', $article->author()->first()->username) }}">{{ $article->author()->first()->getName() }}</a>
        <p class="">{{ $article->created_at->format('F d, Y') }}</p>
    </div>
    <p class="text-5xl font-semibold">{{ $article->title }}</p>
    <a href="{{ route('articles.view', $article->slug) }}">Read article &nearr;</a>
</div>
