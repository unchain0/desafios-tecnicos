@props([
    'icon' => '📋',
    'title' => '',
    'subtitle' => '',
])

<div class="px-6 py-12 text-center">
    <div class="text-gray-400 text-5xl mb-4">{{ $icon }}</div>

    @if ($title)
        <h3 class="text-gray-500 text-lg font-medium">{{ $title }}</h3>
    @endif

    @if ($subtitle)
        <p class="text-gray-400 text-sm mt-2">{{ $subtitle }}</p>
    @endif
    <div class="mt-4">
        {{ $slot }}
    </div>
</div>
