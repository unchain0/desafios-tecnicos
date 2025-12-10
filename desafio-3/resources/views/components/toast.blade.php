@props([
    'message' => '',
    'type' => 'success',
    'duration' => 3000,
])

@php
    $types = [
        'success' => 'bg-green-100 border-green-400 text-green-700',
        'error' => 'bg-red-100 border-red-400 text-red-700',
    ];
    $classes = $types[$type] ?? $types['success'];
@endphp

@if ($message)
    <div
        x-data="{ show: true }"
        x-init="setTimeout(() => { show = false; $wire.clearMessage(); }, {{ $duration }})"
        x-show="show"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        role="alert"
        {{ $attributes->merge(['class' => "mb-6 p-4 border rounded-lg {$classes}"]) }}
    >
        <div class="flex items-start">
            <span class="mr-2">
                @if ($type === 'success')
                    ✅
                @else
                    ⚠️
                @endif
            </span>
            <span>{{ $message }}</span>
        </div>
    </div>
@endif
