@props([
    'type' => 'primary',
    'href' => null,
    'method' => 'GET',
    'confirm' => null,
    'icon' => null,
    'size' => 'md',
    'form' => null,
])

@php
$baseClasses = 'inline-flex items-center font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors';

$sizeClasses = [
    'sm' => 'px-2 py-1 text-xs',
    'md' => 'px-4 py-2 text-sm',
    'lg' => 'px-6 py-3 text-base',
];

$typeClasses = [
    'primary' => 'text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-indigo-500',
    'secondary' => 'text-gray-700 bg-gray-100 hover:bg-gray-200 focus:ring-gray-500',
    'success' => 'text-white bg-green-600 hover:bg-green-700 focus:ring-green-500',
    'danger' => 'text-white bg-red-600 hover:bg-red-700 focus:ring-red-500',
    'info' => 'text-white bg-blue-500 hover:bg-blue-600 focus:ring-blue-500',
];

$classes = $baseClasses . ' ' . $sizeClasses[$size] . ' ' . $typeClasses[$type];
@endphp

@if($href && strtoupper($method) === 'GET')
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon)
            <span class="mr-2">{!! $icon !!}</span>
        @endif
        {{ $slot }}
    </a>
@elseif($href)
    <form action="{{ $href }}" method="POST" class="inline-block" {{ $form ? "id={$form}" : '' }}>
        @csrf
        @method($method)
        <button type="submit" {{ $attributes->merge(['class' => $classes]) }}
            @if($confirm) onclick="return confirm('{{ $confirm }}')" @endif>
            @if($icon)
                <span class="mr-2">{!! $icon !!}</span>
            @endif
            {{ $slot }}
        </button>
    </form>
@else
    <button type="submit" {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon)
            <span class="mr-2">{!! $icon !!}</span>
        @endif
        {{ $slot }}
    </button>
@endif
