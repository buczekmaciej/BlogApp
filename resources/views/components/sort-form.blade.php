<form>
    <select class="sort cursor-pointer bg-transparent border-[1px] border-solid border-neutral-200 rounded-md px-2 py-1 outline-transparent"
            name="{{ $selectKey }}">
        @foreach ($options as $option)
            <option @if (request()->get($selectKey) === $option['value'] || (!request()->has($selectKey) && $option['value'] === $default)) selected @endif
                    value="{{ $option['value'] }}">{{ $option['view'] }}</option>
        @endforeach
    </select>
    @foreach ($_GET as $key => $val)
        @if (!in_array($key, $excludedKeys))
            <input name="{{ $key }}"
                   type="hidden"
                   value="{{ $val }}">
        @endif
    @endforeach
</form>
