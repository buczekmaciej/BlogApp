<div class="absolute top-0 left-0 flex justify-between items-center w-full py-6 px-80">
    <a class=""
       href="{{ session()->get('url.intended') }}">
        <svg class="h-6 fill-slate-50"
             viewBox="0 0 1024 1024"
             xmlns="http://www.w3.org/2000/svg">
            <path d="M872 572H266.8l144.3-183c4.1-5.2.4-13-6.3-13H340c-9.8 0-19.1 4.5-25.1 12.2l-164 208c-16.5 21-1.6 51.8 25.1 51.8h696c4.4 0 8-3.6 8-8v-60c0-4.4-3.6-8-8-8z"></path>
        </svg>
    </a>
    <p class="text-4xl font-medium">Blog</p>
    <p>{{ now()->format('D, F jS, Y') }}</p>
</div>
