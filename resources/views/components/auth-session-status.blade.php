@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'font-semibold text-sm md:text-base text-custom-blue']) }}>
        {{ $status }}
    </div>
@endif
