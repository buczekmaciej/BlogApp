@extends('base')

@section('title', 'Settings')

@section('body')
    <div class="w-2/3 py-16 flex flex-col gap-6">
        <a class="flex items-center gap-2"
           href="{{ route('user.profile', auth()->user()->username) }}">
            <svg class="h-4"
                 fill="none"
                 stroke-linecap="round"
                 stroke-linejoin="round"
                 stroke-width="2"
                 stroke="currentColor"
                 viewBox="0 0 24 24"
                 xmlns="http://www.w3.org/2000/svg">
                <line x1="19"
                      x2="5"
                      y1="12"
                      y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            <span>Back to profile</span>
        </a>

        <form class="flex flex-col gap-6 bg-white rounded-md px-6 py-8 shadow-md"
              enctype="multipart/form-data"
              method="POST">
            <p class="font-bold text-4xl">Update profile</p>
            <div class="flex gap-4">
                <div class="flex flex-col gap-5">
                    <div class="flex flex-col items-center gap-4">
                        <img alt=""
                             class="rounded-md h-80"
                             src="{{ asset('assets/profileImages/' . auth()->user()->image) }}">
                        <input accept="image/png, image/jpeg"
                               class="cursor-pointer border-2 border-gray-2 border-solid p-3 rounded-md"
                               name="image"
                               type="file">
                        <span class='input-help'>Max 5MB, only .png, .jpg, .jpeg</span>
                        @if ($errors->has('image'))
                            @include('components.error', ['error' => $errors->first('image')])
                        @endif
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="text-lg"
                               for="timezone">Timezone</label>
                        <select class="form-input{{ $errors->has('timezone') ? ' form-input-error' : '' }} cursor-pointer"
                                name="timezone">
                            @foreach ($timezones as $tz)
                                <option @if (auth()->user()?->timezone === $tz) selected @endif
                                        value="{{ $tz }}">{{ $tz }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('timezone'))
                            @include('components.error', ['error' => $errors->first('timezone')])
                        @endif
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="text-lg"
                               for="timezone">Birth date</label>
                        <input class="form-input{{ $errors->has('birthDate') ? ' form-input-error' : '' }}"
                               name="birthDate"
                               type="date"
                               value="{{ auth()->user()->birthDate?->format('Y-m-d') }}">
                        @if ($errors->has('birthDate'))
                            @include('components.error', ['error' => $errors->first('birthDate')])
                        @endif
                    </div>
                    @include('components.form-box', [
                        'error' => $errors->has('location') ? $errors->first('location') : null,
                        'id' => 'location',
                        'label' => 'Location',
                        'name' => 'location',
                        'value' => auth()->user()->location,
                        'required' => false,
                    ])
                    <div class="[&>*]:cursor-pointer flex items-center gap-2">
                        <input @if (auth()->user()->isSubscribed) checked @endif
                               id="subscribe"
                               name="isSubscribed"
                               type="checkbox"
                               value="1">
                        <label for="subscribe">Subscribe to newsletter</label>
                    </div>
                </div>
                <div class="w-full flex flex-col gap-4">
                    @include('components.form-box', [
                        'error' => $errors->has('displayName') ? $errors->first('displayName') : null,
                        'id' => 'displayName',
                        'label' => 'Display name',
                        'name' => 'displayName',
                        'value' => auth()->user()->displayName,
                        'required' => false,
                    ])
                    @include('components.form-box', [
                        'error' => $errors->has('email') ? $errors->first('email') : null,
                        'id' => 'email',
                        'label' => 'E-mail',
                        'name' => 'email',
                        'value' => auth()->user()->email,
                    ])
                    @include('components.form-box', [
                        'error' => $errors->has('bio') ? $errors->first('bio') : null,
                        'id' => 'bio',
                        'label' => 'Bio',
                        'name' => 'bio',
                        'value' => auth()->user()->bio,
                        'required' => false,
                        'item' => 'textarea',
                    ])
                    <button class="form-btn">Update</button>
                </div>
            </div>
            @csrf
        </form>
    </div>
@endsection
