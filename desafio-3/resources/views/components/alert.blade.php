@props([
    'type' => 'success',
])

@php
    $types = [
        'success' => 'bg-green-100 border-green-400 text-green-700',
        'error' => 'bg-red-100 border-red-400 text-red-700',
    ];
    $classes = $types[$type] ?? $types['success'];
@endphp

<div {{ $attributes->merge(['class' => "mb-6 p-4 border rounded-lg {$classes}"]) }}>
    {{ $slot }}
</div>
