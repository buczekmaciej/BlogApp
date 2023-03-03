<div class="w-full h-[40rem] relative text-slate-50 article-bg-gradient overflow-hidden flex items-center">
    @if ($article->thumbnail)
        <img alt=""
             class="rounded-md w-full opacity-50"
             src="{{ asset('assets/images/' . $article->uuid . '/' . $article->thumbnail) }}">
    @endif
    <div class="flex flex-col gap-3 absolute bottom-12 left-32">
        <div class="flex gap-4 items-center">
            <a class="bg-orange-800 capitalize px-3 py-1 rounded-md"
               href="{{ route('tags.view', $article->tags()->first()->name) }}">{{ $article->tags()->first()->name }}</a>
            <a class=""
               href="{{ route('user.profile', $article->author()->first()->username) }}">{{ $article->author()->first()->getName() }}</a>
            <span class="h-2 w-0.5 bg-slate-50"></span>
            <p class="">{{ $article->created_at->format('F d, Y') }}</p>
        </div>
        <p class="text-5xl font-semibold max-w-[55%]">{{ ucfirst($article->title) }}</p>
        <a class="mt-3"
           href="{{ route('articles.view', $article->slug) }}">Read article &nearr;</a>
    </div>
</div>
