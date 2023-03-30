@props(['value'])

<label {{ $attributes->merge(['class' => 'custom-label']) }}>
    {{ $value ?? $slot }}
</label>
