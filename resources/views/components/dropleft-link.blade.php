@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full py-2 pr-4 hover:pr-3 pl-4 text-left hover:border-r-[20px] hover:border-custom-light-blue hover:bg-sky-50 hover:border-opacity-50 transition duration-300 ease-in-out text-sm lg:text-base font-medium leading-5 text-custom-light-blue hover:text-opacity-80'
            : 'block w-full py-2 pr-4 hover:pr-3 pl-4 text-left hover:border-r-[20px] hover:border-custom-light-blue hover:bg-sky-50 hover:border-opacity-50 transition duration-300 ease-in-out text-sm lg:text-base font-medium leading-5 text-custom-dark hover:text-custom-light-blue';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>

{{-- <a {{ $attributes->merge(['class' => 'block w-full py-2 pr-4 hover:pr-3 pl-4 text-left hover:border-r-[20px] hover:border-custom-light-blue hover:bg-sky-50 hover:border-opacity-50 transition duration-300 ease-in-out text-sm lg:text-base font-medium leading-5 text-custom-dark hover:text-custom-light-blue']) }}>{{ $slot }}</a> --}}
