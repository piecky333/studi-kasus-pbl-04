@props(['active'])

@php

$activeClasses = 'block w-full ps-3 pe-4 py-2 border-l-4 border-yellow-400 text-start text-base font-medium text-yellow-300 bg-blue-800 transition duration-150 ease-in-out';

$inactiveClasses = 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-white hover:text-gray-300 hover:bg-blue-600 focus:outline-none focus:text-gray-300 focus:bg-blue-600 transition duration-150 ease-in-out';

$classes = ($active ?? false) ? $activeClasses : $inactiveClasses;
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>