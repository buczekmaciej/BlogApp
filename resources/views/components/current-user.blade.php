@if (auth()->user()->image && auth()->user()->displayName)
    <div class="flex items-center gap-2">
        <img alt=""
             class="h-12 rounded-xl"
             src="{{ asset('assets/profileImages/' . auth()->user()->image) }}">
        <p class="">{{ auth()->user()->displayName }}</p>
    </div>
@else
    <a class="text-gray-400 text-sm"
       href="">Configure your profile</a>
@endif
