@props([
    'icon' => '',
    'title' => '',
    'subtitle' => '',
])

<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-2">
        @if ($icon)
            {{ $icon }}
        @endif{{ $title }}
    </h1>
    @if ($subtitle)
        <p class="text-gray-600">{{ $subtitle }}</p>
    @endif
</div>
