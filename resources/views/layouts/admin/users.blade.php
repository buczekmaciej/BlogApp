@extends('layouts.admin.base')

@section('title', 'Users')

@section('content')
    <div class="bg-container {{ $errors->any() ? 'grid' : 'hidden' }} fixed grid place-items-center top-0 left-0 z-40 bg-neutral-800/90 h-screen w-full">
        <form action="{{ route('admin.users.changeRoles', 'username') }}"
              class="roles-form w-1/4 p-8 hidden flex-col gap-6 rounded-md bg-slate-50"
              method="POST">
            @csrf
            <p class="text-xl font-semibold">Update roles</p>
            <p>Roles of <span class="role-owner"></span></p>
            <input name="roles[]"
                   type="hidden"
                   value="USER">
            <div class="flex gap-2 cursor-pointer [&>*]:cursor-pointer">
                <input id="writer"
                       name="roles[]"
                       type="checkbox"
                       value="WRITER">
                <label for="writer">Writer</label>
            </div>
            <div class="flex gap-2 cursor-pointer [&>*]:cursor-pointer">
                <input id="admin"
                       name="roles[]"
                       type="checkbox"
                       value="ADMIN">
                <label for="admin">Admin</label>
            </div>
            @if ($errors->any())
                @include('components.error', ['error' => 'Form wasn\'t complete during last submit, try again'])
            @endif
            <div class="flex items-center justify-end gap-4 w-full">
                <button class="edit-close"
                        type="button">Close</button>
                <button class="form-btn">Update</button>
            </div>
        </form>
    </div>
    <div class="w-3/4 flex flex-col gap-8 py-10">
        <div class="flex items-center justify-between gap-4">
            {!! $users->links('vendor.pagination.tailwind', ['onlyCounter' => true]) !!}
            <form class="flex items-center gap-2">
                <p class="">Order by:</p>
                @include('components.sort-form', [
                    'options' => [
                        ['value' => 'uuid-asc', 'view' => 'UUID growing'],
                        ['value' => 'uuid-desc', 'view' => 'UUID decreasing'],
                        ['value' => 'username-asc', 'view' => 'Userame growing'],
                        ['value' => 'username-desc', 'view' => 'Userame decreasing'],
                        ['value' => 'email-asc', 'view' => 'E-mail growing'],
                        ['value' => 'email-desc', 'view' => 'E-mail decreasing'],
                        ['value' => 'role-asc', 'view' => 'Lower role first'],
                        ['value' => 'role-desc', 'view' => 'Higher role first'],
                        ['value' => 'isDisabled-asc', 'view' => 'Disabled last'],
                        ['value' => 'isDisabled-desc', 'view' => 'Disabled first'],
                        ['value' => 'created_at-asc', 'view' => 'Joined growing'],
                        ['value' => 'created_at-desc', 'view' => 'Joined decreasing'],
                        ['value' => 'updated_at-asc', 'view' => 'Updated growing'],
                        ['value' => 'updated_at-desc', 'view' => 'Updated decreasing'],
                    ],
                    'selectKey' => 'order',
                    'default' => 'username-asc',
                    'excludedKeys' => ['order'],
                ])
            </form>
        </div>
        <div class="w-full flex flex-col gap-4">
            <div class="w-full flex gap-4 py-4 pl-6 bg-slate-100">
                <p class="font-semibold flex-[2] text-center">UUID</p>
                <p class="font-semibold flex-[2] text-center">Username</p>
                <p class="font-semibold flex-[3] text-center">E-mail</p>
                <p class="font-semibold flex-[2] text-center">Role</p>
                <p class="font-semibold flex-1 text-center">Disabled</p>
                <p class="font-semibold flex-[2] text-center">Joined</p>
                <p class="font-semibold flex-1 text-center">Updated</p>
                <p class="font-semibold flex-1 text-center">Actions</p>
            </div>
            @foreach ($users as $user)
                <div class="w-full flex gap-4 border-b-[1px] border-solid border-gray-200 last:border-0 py-4 pl-6 first:pt-0">
                    <p class="flex-[2] truncate">{{ $user->uuid }}</p>
                    <a class="flex-[2] text-center"
                       href="{{ route('user.profile', $user->username) }}">{{ $user->username }}</a>
                    <p class="flex-[3] text-center">{{ $user->email }}</p>
                    <p class="flex-[2] text-center">{{ $user->getRole() }}</p>
                    <p class="flex-1 text-center">{{ $user->isDisabled }}</p>
                    <p class="flex-[2] text-center">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                    <p class="flex-1 text-center">{{ $user->updated_at->diffInDays(now()) }}d ago</p>
                    <div class="flex gap-6 items-center justify-center flex-1">
                        <p class="edit-btn cursor-pointer"
                           data-username="{{ $user->username }}">
                            <svg class="h-4 fill-blue-600"
                                 viewBox="0 0 512 512"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M290.74 93.24l128.02 128.02-277.99 277.99-114.14 12.6C11.35 513.54-1.56 500.62.14 485.34l12.7-114.22 277.9-277.88zm207.2-19.06l-60.11-60.11c-18.75-18.75-49.16-18.75-67.91 0l-56.55 56.55 128.02 128.02 56.55-56.55c18.75-18.76 18.75-49.16 0-67.91z"></path>
                            </svg>
                        </p>
                        <a href="{{ route('admin.users.disable', $user->username) }}">
                            <svg class="h-4 fill-red-600"
                                 viewBox="0 0 640 512"
                                 xmlns="http://www.w3.org/2000/svg">
                                @if ($user->isDisabled)
                                    <path d="M288 144a110.94 110.94 0 0 0-31.24 5 55.4 55.4 0 0 1 7.24 27 56 56 0 0 1-56 56 55.4 55.4 0 0 1-27-7.24A111.71 111.71 0 1 0 288 144zm284.52 97.4C518.29 135.59 410.93 64 288 64S57.68 135.64 3.48 241.41a32.35 32.35 0 0 0 0 29.19C57.71 376.41 165.07 448 288 448s230.32-71.64 284.52-177.41a32.35 32.35 0 0 0 0-29.19zM288 400c-98.65 0-189.09-55-237.93-144C98.91 167 189.34 112 288 112s189.09 55 237.93 144C477.1 345 386.66 400 288 400z"></path>
                                @else
                                    <path
                                          d="M634 471L36 3.51A16 16 0 0 0 13.51 6l-10 12.49A16 16 0 0 0 6 41l598 467.49a16 16 0 0 0 22.49-2.49l10-12.49A16 16 0 0 0 634 471zM296.79 146.47l134.79 105.38C429.36 191.91 380.48 144 320 144a112.26 112.26 0 0 0-23.21 2.47zm46.42 219.07L208.42 260.16C210.65 320.09 259.53 368 320 368a113 113 0 0 0 23.21-2.46zM320 112c98.65 0 189.09 55 237.93 144a285.53 285.53 0 0 1-44 60.2l37.74 29.5a333.7 333.7 0 0 0 52.9-75.11 32.35 32.35 0 0 0 0-29.19C550.29 135.59 442.93 64 320 64c-36.7 0-71.71 7-104.63 18.81l46.41 36.29c18.94-4.3 38.34-7.1 58.22-7.1zm0 288c-98.65 0-189.08-55-237.93-144a285.47 285.47 0 0 1 44.05-60.19l-37.74-29.5a333.6 333.6 0 0 0-52.89 75.1 32.35 32.35 0 0 0 0 29.19C89.72 376.41 197.08 448 320 448c36.7 0 71.71-7.05 104.63-18.81l-46.41-36.28C359.28 397.2 339.89 400 320 400z">
                                    </path>
                                @endif
                            </svg>
                        </a>
                        <a href="{{ route('admin.users.delete', $user->username) }}">
                            <svg class="h-4 fill-red-600"
                                 viewBox="0 0 448 512"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M432 32H312l-9.4-18.7A24 24 0 0 0 281.1 0H166.8a23.72 23.72 0 0 0-21.4 13.3L136 32H16A16 16 0 0 0 0 48v32a16 16 0 0 0 16 16h416a16 16 0 0 0 16-16V48a16 16 0 0 0-16-16zM53.2 467a48 48 0 0 0 47.9 45h245.8a48 48 0 0 0 47.9-45L416 128H32z"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
        {!! $users->withQueryString()->links() !!}
    </div>
@endsection

@section('javascripts')
    <script src="{{ asset('js/select.js') }}"></script>
    <script src="{{ asset('js/adminUser.js') }}"></script>
@endsection
