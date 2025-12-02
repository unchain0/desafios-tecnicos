@props([
    'variant' => 'primary',
    'type' => 'button',
    'disabled' => false,
])

@php
    $variants = [
        'primary' => 'bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-500',
        'success' => 'bg-green-100 text-green-700 hover:bg-green-200',
        'warning' => 'bg-yellow-100 text-yellow-700 hover:bg-yellow-200',
        'danger' => 'bg-red-100 text-red-700 hover:bg-red-200',
    ];
    $classes = $variants[$variant] ?? $variants['primary'];
@endphp

<button
    type="{{ $type }}"
    {{ $disabled ? 'disabled' : '' }}
    {{ $attributes->merge([
        'class' => "px-4 py-2 text-sm font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 {$classes}"
    ]) }}
>
    {{ $slot }}
</button>
