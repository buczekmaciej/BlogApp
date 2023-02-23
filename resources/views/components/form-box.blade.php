<div class="flex flex-col gap-2">
    <label class="text-lg"
           for="{{ $id }}">{{ $label }}</label>
    <input class="form-input{{ $errors->any() ? ' form-input-error' : '' }}"
           id="{{ $id }}"
           name="{{ $name }}"
           required
           type="{{ $type ?? 'text' }}">
</div>
