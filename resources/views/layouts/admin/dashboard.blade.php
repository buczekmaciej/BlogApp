@extends('layouts.admin.base')

@section('title', 'Dashboard')

@section('content')
    <div class="w-3/4 grid grid-cols-4 gap-8 py-10">
        <p class="col-span-4 font-semibold text-2xl">Overview</p>
        @foreach ($data as $item => $count)
            <div class="shadow-md p-6 rounded-md flex flex-col gap-2 tracking-wide">
                <p class="text-sm font-light text-gray-400">{{ ucfirst(str_replace('_', ' ', $item)) }}</p>
                <p class="text-3xl">{{ $count }}</p>
            </div>
        @endforeach
    </div>
@endsection
