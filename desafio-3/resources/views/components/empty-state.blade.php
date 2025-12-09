@props([
    'icon' => '📋',
    'title' => '',
    'subtitle' => '',
])

<div class="px-6 py-12 text-center">
    <div class="text-gray-400 text-5xl mb-4">{{ $icon }}</div>
    @if ($title)
        <p class="text-gray-500">{{ $title }}</p>
    @endif
    @if ($subtitle)
        <p class="text-gray-400 text-sm mt-1">{{ $subtitle }}</p>
    @endif
    {{ $slot }}
</div>
