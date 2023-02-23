<div class="flex flex-col gap-2 w-full">
    @foreach ($errors as $error)
        <p class="rounded-md px-6 py-3 font-medium border-2 border-solid border-red-500 bg-red-500/20 text-red-500">{{ $error }}</p>
    @endforeach
</div>
