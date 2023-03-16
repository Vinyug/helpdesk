@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full pl-4 pr-4 py-2 text-left hover:border-l-4 hover:border-custom-light-blue hover:border-opacity-50 hover:pl-3 text-base font-medium leading-5 text-custom-light-blue hover:text-custom-light-blue transition duration-300 ease-in-out'
            : 'block w-full pl-4 pr-4 py-2 text-left hover:border-l-4 hover:border-custom-light-blue hover:border-opacity-50 hover:pl-3 text-base font-medium leading-5 text-custom-dark hover:text-custom-light-blue transition duration-300 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
