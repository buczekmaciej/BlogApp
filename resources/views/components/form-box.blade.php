<div class="flex flex-col gap-2">
    <label class="text-lg"
           for="{{ $id }}">
        {{ $label }}
        @if (!isset($required) || $required)
            <sup class="text-red-600">*</sup>
        @endif
    </label>
    @if (!isset($item) || $item === 'input')
        <input class="form-input{{ $error ? ' form-input-error' : '' }}"
               id="{{ $id }}"
               name="{{ $name }}"
               type="{{ $type ?? 'text' }}"
               value="{{ $value }}">
    @elseif($item === 'textarea')
        <textarea @if (!isset($required) || $required) required @endif
                  class="form-textarea{{ $error ? ' border-red-500' : '' }}"
                  id="{{ $id }}"
                  name="{{ $name }}">{!! $value !!}</textarea>
    @endif
    @if (isset($extra))
        {!! $extra !!}
    @endif
    @if (isset($error))
        @include('components.error', ['error' => $error])
    @endif
</div>
