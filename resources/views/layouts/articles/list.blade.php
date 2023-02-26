@extends('base')

@section('title', 'Articles')

@section('body')
    <div class="px-24 py-10 w-full flex flex-col gap-6">
        <div class="flex justify-between items-center">
            <p class="font-semibold text-3xl">Articles</p>
            <div class="flex gap-4 items-center">
                @include('components.sort-form', [
                    'options' => [
                        [
                            'value' => '15',
                            'view' => '15',
                        ],
                        [
                            'value' => '30',
                            'view' => '30',
                        ],
                        [
                            'value' => '45',
                            'view' => '45',
                        ],
                    ],
                    'selectKey' => 'perpage',
                    'default' => '15',
                    'excludedKeys' => ['perpage'],
                ])
                @include('components.sort-form', [
                    'options' => [
                        [
                            'value' => 'title_asc',
                            'view' => 'Title - A to Z',
                        ],
                        [
                            'value' => 'title_desc',
                            'view' => 'Title - Z to A',
                        ],
                        [
                            'value' => 'date_asc',
                            'view' => 'Date - From earliest',
                        ],
                        [
                            'value' => 'date_desc',
                            'view' => 'Date - From latest',
                        ],
                    ],
                    'selectKey' => 'order',
                    'default' => 'date_desc',
                    'excludedKeys' => ['order'],
                ])
                <a href="{{ request()->has('page') ? route('articles.list') . '?page=' . request()->get('page') : route('articles.list') }}">Reset</a>
            </div>
        </div>
        <div class="grid grid-cols-3 gap-4">
            @foreach ($articles as $article)
                @include('components.article', ['article' => $article, 'format' => 'F d, Y'])
            @endforeach
        </div>
        {{ $articles->withQueryString()->links() }}
    </div>
@endsection

@section('javascripts')
    <script src="{{ asset('js/select.js') }}"></script>
@endsection
