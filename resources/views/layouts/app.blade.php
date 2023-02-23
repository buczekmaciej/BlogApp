@extends('base')

@section('title', 'Homepage')

@section('body')
    @include('components.title-article', ['article' => $article])
@endsection
