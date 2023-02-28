<div class="flex flex-col gap-2">
    <label class="text-lg"
           for="{{ $id }}">{{ $label }}</label>
    @if (!isset($item) || $item === 'input')
        <input class="form-input{{ $error ? ' form-input-error' : '' }}"
               id="{{ $id }}"
               name="{{ $name }}"
               required
               type="{{ $type ?? 'text' }}"
               value="{{ $value }}">
    @elseif($item === 'textarea')
        <textarea class="form-textarea{{ $error ? ' border-red-500' : '' }}"
                  id="{{ $id }}"
                  name="{{ $name }}"
                  required>{{ $value }}</textarea>
    @endif
    @if (isset($extra))
        {!! $extra !!}
    @endif
    @if (isset($error))
        @include('components.error', ['error' => $error])
    @endif
</div>
