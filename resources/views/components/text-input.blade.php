@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-300 focus:border-custom-blue focus:ring-custom-blue rounded-sm shadow-sm transition duration-300 ease-in-out']) !!}>
