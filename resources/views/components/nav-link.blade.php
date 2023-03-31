@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center ml-6 lg:ml-10  h-full text-sm lg:text-base font-bold leading-5 text-custom-dark-blue transition duration-300 ease-in-out hover:text-opacity-80 hover:text-custom-light-blue cursor-pointer'
            : 'inline-flex items-center ml-6 lg:ml-10 h-full text-sm lg:text-base font-bold leading-5 text-custom-light-blue transition duration-300 ease-in-out hover:text-opacity-80 cursor-pointer';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
