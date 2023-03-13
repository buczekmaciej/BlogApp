@extends('layouts.admin.base')

@section('title', 'Reports')

@section('content')
    <div class="w-3/4 flex flex-col gap-8 py-10">
        <div class="flex items-center justify-start gap-4">
            {!! $reports->links('vendor.pagination.tailwind', ['onlyCounter' => true]) !!}
            <form class="flex items-center gap-2 ml-auto">
                <p class="">Order by:</p>
                @include('components.sort-form', [
                    'options' => [
                        ['value' => 'reports.uuid-asc', 'view' => 'UUID growing'],
                        ['value' => 'reports.uuid-desc', 'view' => 'UUID decreasing'],
                        ['value' => 'reason-asc', 'view' => 'Reason growing'],
                        ['value' => 'reason-desc', 'view' => 'Reason decreasing'],
                        ['value' => 'users.username-asc', 'view' => 'Author growing'],
                        ['value' => 'users.username-desc', 'view' => 'Author decreasing'],
                        ['value' => 'article-desc', 'view' => 'Article reports first'],
                        ['value' => 'comment-desc', 'view' => 'Comment reports first'],
                        ['value' => 'created_at-asc', 'view' => 'Created growing'],
                        ['value' => 'created_at-desc', 'view' => 'Created decreasing'],
                        ['value' => 'updated_at-asc', 'view' => 'Updated growing'],
                        ['value' => 'updated_at-desc', 'view' => 'Updated decreasing'],
                    ],
                    'selectKey' => 'order',
                    'default' => 'created_at-asc',
                    'excludedKeys' => ['order'],
                ])
            </form>
        </div>
        <div class="w-full flex flex-col gap-4">
            <div class="w-full flex gap-4 py-4 pl-6 bg-slate-100">
                <p class="font-semibold flex-[2] text-center">UUID</p>
                <p class="font-semibold flex-[3] text-center">Reason</p>
                <p class="font-semibold flex-[2] text-center">Author</p>
                <p class="font-semibold flex-[2] text-center">Article</p>
                <p class="font-semibold flex-[2] text-center">Comment</p>
                <p class="font-semibold flex-[2] text-center">Created</p>
                <p class="font-semibold flex-1 text-center">Updated</p>
                <p class="font-semibold flex-1 text-center">Actions</p>
            </div>
            @forelse ($reports as $report)
                <div class="w-full flex gap-4 border-b-[1px] border-solid border-gray-200 group last:border-0 py-4 pl-6 first:pt-0">
                    <p class="flex-[2] truncate">{{ $report->uuid }}</p>
                    <p class="flex-[3]">{{ $report->reason }}</p>
                    @if ($report->author)
                        <a class="flex-[2] truncate text-center text-blue-800 font-bold"
                           href="{{ route('user.profile', $report->author->username) }}"
                           target="_blank">{{ $report->author->displayName ? $report->author->displayName . " (@{$report->author->username})" : "@{$report->author->username}" }}</a>
                    @else
                        <p class="flex-[2] text-center text-gray-400">Null</p>
                    @endif
                    @if ($report->article)
                        <a class="flex-[2] truncate text-center text-blue-800 font-bold"
                           href="{{ route('articles.view', $report->article->slug) }}"
                           target="_blank">Open article</a>
                    @else
                        <p class="flex-[2] text-center text-gray-400">Null</p>
                    @endif
                    @if ($report->comment)
                        <p class="avalaible cursor-pointer flex-[2] truncate text-center"
                           data-uuid="{{ $report->uuid }}"
                           title="Copy uuid of comment">{{ $report->comment->content }}</p>
                    @else
                        <p class="flex-[2] text-center text-gray-400">Null</p>
                    @endif
                    <p class="flex-[2] text-center">{{ $report->created_at->format('d/m/Y H:i') }}</p>
                    <p class="flex-1 text-center">{{ $report->updated_at->diffInDays(now()) }}d ago</p>
                    <div class="flex gap-6 items-center justify-center flex-1 opacity-0 group-hover:opacity-100">
                        <a href="{{ route('admin.reports.delete', $report->uuid) }}">
                            <svg class="h-4 fill-red-600"
                                 viewBox="0 0 448 512"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M432 32H312l-9.4-18.7A24 24 0 0 0 281.1 0H166.8a23.72 23.72 0 0 0-21.4 13.3L136 32H16A16 16 0 0 0 0 48v32a16 16 0 0 0 16 16h416a16 16 0 0 0 16-16V48a16 16 0 0 0-16-16zM53.2 467a48 48 0 0 0 47.9 45h245.8a48 48 0 0 0 47.9-45L416 128H32z"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            @empty
                <div class="w-full text-center py-4">
                    <p>There's nothing to show</p>
                </div>
            @endforelse
        </div>
        {!! $reports->withQueryString()->links() !!}
    </div>
@endsection

@section('javascripts')
    <script src="{{ asset('js/select.js') }}"></script>
    <script src="{{ asset('js/copy.js') }}"></script>
@endsection
